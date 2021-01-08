<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOwn extends Model
{
    protected $table = 'users';
    public function parent()
	{
	    return $this->belongsTo(self::class, 'id');
	}

	public function children()
	{
	    return $this->hasMany(self::class, 'id_operator');
	}
}
