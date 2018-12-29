<?php

namespace App;

use App\Http\Traits\AuthAdminTrait;
use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable, SoftDeletes, Searchable, AuthAdminTrait;

    protected $table='admins';

    protected $fillable = [
        'mobile', 'name', 'status', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

}
