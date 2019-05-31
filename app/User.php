<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['username','email','password','phone_no'];
    protected $hidden =['password'];

    public function events()
    {
        return $this->belongsToMany('App\Event');
    }

}


