<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayNow extends Model
{
    protected $table = 'orderPayNowToken';
    public $timestamps = false;
}
