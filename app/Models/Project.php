<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $table = 'projects';
    public $timestamps = true;

    public function stacks()
    {
        return $this->belongsToMany('App\Stack');
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function feedbacks()
    {
        return $this->hasMany('App\Feedback');
    }

    public function type()
    {
        return $this->belongsTo('App\Type');
    }

    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('role');
    }

}
