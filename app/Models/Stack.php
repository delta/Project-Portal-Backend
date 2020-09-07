<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stack extends Model
{

    protected $table = 'stacks';
    public $timestamps = false;

    public function projects()
    {
        return $this->belongsToMany('App\Project');
    }

}
