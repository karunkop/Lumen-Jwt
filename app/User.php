<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['username','email','password','phone_no','address'];
    protected $hidden =['password'];

    public function events()
    {
        return $this->belongsToMany('App\Event');
    }

}


