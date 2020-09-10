<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $table = 'projects';
    protected $hidden = ['pivot', 'type_id', 'status_id'];
    public $timestamps = true;

    public function stacks()
    {
        return $this->belongsToMany('App\Models\Stack');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }

    public function feedbacks()
    {
        return $this->hasMany('App\Models\Feedback');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User')->withPivot('role');
    }

}
