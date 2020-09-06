<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    protected $table = 'statuses';
    public $timestamps = false;

    public function projects()
    {
        return $this->hasMany('App\Project');
    }

}
