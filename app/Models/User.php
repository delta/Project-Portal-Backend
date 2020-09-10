<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable
{
    use HasFactory;

    use Notifiable, HasApiTokens;

    use SoftDeletes;

    protected $table = 'users';
    protected $hidden = ['email', 'password'];
    protected $fillable = ['name', 'email', 'password','roll_number','github_handle'];
    public $timestamps = true;

    public function projects()
    {
        return $this->belongsToMany('App\Models\Project')
                    ->withPivot('role')
                    ->whereNull('project_user.deleted_at');
    }
}
