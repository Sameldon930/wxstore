<?php

namespace App\Http\Traits;


use App\PermissionRole;
use App\Services\WebServices\PermissionService;

trait AuthAdminTrait
{
    static $role_admin = 'admin';

    public function hasRole($role){
        return $this->roles->contains('name', $role);
    }

    public function withoutRole($role){
        return !$this->hasRole($role);
    }

        public function hasPermission($permission){
            foreach ($this->roles as $role){
                if ($role->pivots()->pluck('permission_id')->contains($permission)){
                    return true;
                }
            }

            return false;
        }

        public function hasGroup($group, $routes = false){
            if (false === $routes){
                $routes = $this->getPermissions()->all();
            }

            foreach($routes as $route){
                $mca = explode('.', $route);
                if (in_array($group, PermissionService::getIgnoreGroups())){
                    return true;
                }
                if ($group === $mca[1]){
                    return true;
                }
            }

        return false;
    }

    public function withoutGroup($group, $routes = false){
        return !$this->hasGroup($group, $routes);
    }

    public function getPermissions(){
        return PermissionRole::whereIn('role_id', self::roles()->pluck('id'))->pluck('permission_id');
    }

    public function filterPermissions(array $permissions){
        return array_intersect($permissions, self::getPermissions());
    }

    public function canPass($route){
        if (self::isAdmin()){
            return true;
        }

        if (PermissionService::isIgnored($route)){
            return true;
        }

        if (self::hasPermission($route)){
            return true;
        }

        return false;
    }

    public function withoutPermission($permission){
        return !$this->hasPermission($permission);
    }

    public function isAdmin(){
        return self::hasRole(static::$role_admin);
    }

}
