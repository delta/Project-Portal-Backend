<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stack extends Model
{

    protected $table = 'stacks';
    public $timestamps = false;

    public function projects()
    {
        return $this->belongsToMany('App\Models\Project');
    }

}
