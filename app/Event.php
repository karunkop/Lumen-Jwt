<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name','description','start_date_time','end_date_time', 'host', 'completed'];
    public function host(){
        return $this->belongsTo('App\User','host','id');
    }
    public function participants()
    {
        return $this->belongsToMany('App\User');
    }
    public function group()
    {
        return $this->hasOne('App\Group');
    }
}
