<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['name','email','password'];
    protected $hidden =['password'];

    public function events()
    {
        return $this->belongsToMany('App\Event');
    }

}


