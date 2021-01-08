<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceUser extends Model
{
    protected $table = 'user_of_device';
    public $timestamps = false;
}
