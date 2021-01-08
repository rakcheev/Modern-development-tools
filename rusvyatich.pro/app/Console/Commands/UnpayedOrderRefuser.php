<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ChangeOrder;

class UnpayedOrderRefuser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UnpayedOrderRefuser:refuse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refuse unpayed orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Refuse old");
        ChangeOrder::eraseUnpayed();   
    }
}
