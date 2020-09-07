<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'users';
    public $timestamps = true;

    public function projects()
    {
        return $this->belongsToMany('App\Models\Project')->withPivot('role');
    }

}
