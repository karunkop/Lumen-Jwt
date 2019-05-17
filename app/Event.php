<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name','description','location'];

   public function users()
   {
       return $this->belongsToMany('App\User');
   }


}

