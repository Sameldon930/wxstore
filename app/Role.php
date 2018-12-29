<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'display', 'created_at', 'updated_at', 'deleted_at',
    ];

    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }

    public function admins(){
        return $this->belongsToMany(Admin::class);
    }

    public function pivots(){
        return $this->hasMany(PermissionRole::class);
    }

    public function isAdminRole(){
        return $this->name === Admin::$role_admin;
    }
}
