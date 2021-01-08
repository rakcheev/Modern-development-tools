<?php

/*
*
*Модель отправки смс
*
*с помощью Zelenin и sms.ru
*
*/

namespace App;

use App\Classes\Sms\SmsRu;

class Sms 
{
	/*На вход телефон(в любом виде с 7-ки) текст сообщения*/
    public function sendSms($phone, $message)
    {
    	$goodCode = [100,101,102,103,110];
		$client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\ApiIdAuth('3446865C-3D5A-A500-B549-4B2AE31A2B70'));
		$phoneForSms = preg_replace('/[^0-9]/', '', $phone);
		$phoneForSms = '79251955978';//
		$sms1 = new \Zelenin\SmsRu\Entity\Sms($phoneForSms, $message/*, 'SuzdalForge'*/);
		return true;
		if ($client->myBalance()->balance < 3) {
			return false;
		};

		$send = $client->smsSend($sms1);
		return in_array($send->code, $goodCode);
    }
}
