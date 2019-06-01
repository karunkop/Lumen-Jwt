<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['username','f_name','l_name','email','password','phone_no','address','permission','bio'];
    protected $hidden =['password'];

    public function location(){
        return $this->hasOne('App\Location');
    }
}


