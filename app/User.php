<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'users';
    public $timestamps = true;

    public function projects()
    {
        return $this->belongsToMany('App\Project')->withPivot('role');
    }

}
