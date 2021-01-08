<?php

namespace App\Classes\Socket;

use App\Classes\Socket\Base\BaseSocket;
use Ratchet\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use App\OutFromOrder;

class ChatSocket extends BaseSocket 
{
    protected $clients;
    private $subscriptions;
    private $users;
    private $outers;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->users = [];
        $this->outers = [];
        $this->subscriptions = array(array());
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo "connect {$conn->resourceId}\n";
        $this->clients->attach($conn);
        $this->users[$conn->resourceId] = $conn;
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {
        $data = json_decode($msg);
        switch ($data->command) {
            case "subscribe":
                $this->subscriptions[$conn->resourceId][] = $data->channel;
                break;
            case "unsubscribe":
                $key = array_search($data->channel, $this->subscriptions[$conn->resourceId]);
                unset($this->subscriptions[$conn->resourceId][$key]);
                break;
            case "message":
                if (isset($this->subscriptions[$conn->resourceId])) {
                    $orderTarget = '';
                    $targets = $this->subscriptions[$conn->resourceId];

                    foreach ($targets as $target) {
                        $targetRep = preg_replace('/[^a-zA-Z]/', '', $target ); 
                        switch ($targetRep) {
                            case 'constructOrder':
                            case 'imageOrder':
                            case 'cartOrder':
                                $orderTarget = $target;
                                break;
                        }
                    }

                    foreach ($this->subscriptions as $id=>$channels) {
                        $flagStopSend = false;
                        if (isset($channels)) {
                            $flag = true;

                            foreach ($channels as $idChannel => $channel) { 
                                $channelStr = preg_replace('/[^a-zA-Z]/', '', $channel ); 
                                switch ($channelStr) {
                                    case 'constructOrder':
                                    case 'imageOrder':
                                    case 'cartOrder':
                                        if($orderTarget){
                                            if($orderTarget != $channel){
                                                $flagStopSend = true;
                                                break;
                                            }
                                        }    
                                        break;
                                }
                            }

                            if(!$flagStopSend) {

                                foreach ($channels as $idChannel => $channel) { 
                                    if (in_array($channel, $targets) && $id != $conn->resourceId && $flag) {
                                        $this->users[$id]->send($data->message);
                                        $flag = false;
                                    }
                                }

                            }
                        }
                    }
                }
                break;
            case 'imonline':
                $out = OutFromOrder::find($data->id);
                $out->online = ONLINE;
                $out->save();
                $this->outers[$conn->resourceId] = $data->id;
                $this->allertOnline($conn);
                break;
        }
    }

    public function allertOnline(ConnectionInterface $conn)
    {
        if (isset($this->subscriptions[$conn->resourceId])) {
            $orderTarget = '';
            $targets = $this->subscriptions[$conn->resourceId];
            foreach ($targets as $target) {
                $targetRep = preg_replace('/[^a-zA-Z]/', '', $target ); 
                switch ($targetRep) {
                    case 'constructOrder':
                    case 'imageOrder':
                    case 'cartOrder':
                        $orderTarget = $target;
                        break;
                }
            } 
            if(!$orderTarget) return false;
            foreach ($this->subscriptions as $id=>$channels) {
                if (isset($channels)) {
                    foreach ($channels as $idChannel => $channel) { 
                        if ($channel == $orderTarget && $id != $conn->resourceId) {
                            $this->users[$id]->send('changeOnline');
                        }
                    }
                }
            }
        }

    }

    public function onClose(ConnectionInterface $conn)
    {
        if (isset($this->outers[$conn->resourceId])) {
            $out = OutFromOrder::find($this->outers[$conn->resourceId]);
            $out->online = NOT_ONLINE;
            $out->time_outer = DB::raw('NOW()');
            $out->save();
            $this->allertOnline($conn);
            unset($this->outers[$conn->resourceId]);
        }
        $this->clients->detach($conn);
        unset($this->users[$conn->resourceId]);
        unset($this->subscriptions[$conn->resourceId]);
        echo "Disconnect {$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
