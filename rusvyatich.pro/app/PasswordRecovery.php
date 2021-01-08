<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordRecovery extends Model
{
    protected $table = 'password_recovery';
    public $timestamps = false;
}
