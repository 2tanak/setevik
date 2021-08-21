<?php

namespace App\Traits;

use App\Role;
use App\Permission;

/**
 * @package App\Traits
 */
trait HasRolesAndPermissions
{
    /**
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id');
    }

    /**
     * Checking role by argument list
     * @param mixed ...$roles
     * @return bool
     */
    public function hasRole(... $roles)
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checking role by array
     * @param array $roles
     * @return bool
     */
    public function hasRoles(array $roles)
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checking permission
     * @param Permission|string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $slug = ($permission instanceof Permission) ? $permission->slug : $permission;

        return (bool) $this->permissions->where('slug', $slug)->count();
    }

    public function hasPermissions(array $permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checking permission with all variants
     * @param Permission $permission
     * @return bool
     */
    public function hasPermissionTo($permission)
    {
        $permission = ($permission instanceof Permission) ? $permission : Permission::where('slug', $permission)->first();
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    /**
     * Checking permission by roles
     * @param Permission $permission
     * @return bool
     */
    public function hasPermissionThroughRole($permission)
    {
        foreach ($permission->roles as $role) {
            if ($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

//    protected function getAllPermissions(array $permissions)
//    {
//        return Permission::whereIn('slug', $permissions)->get();
//    }
//
//    public function givePermissionsTo(... $permissions)
//    {
//        $permissions = $this->getAllPermissions($permissions);
//        if ($permissions === null) {
//            return $this;
//        }
//        $this->permissions()->saveMany($permissions);
//        return $this;
//    }
//
//    public function deletePermissions(... $permissions)
//    {
//        $permissions = $this->getAllPermissions($permissions);
//        $this->permissions()->detach($permissions);
//        return $this;
//    }
//
//    public function refreshPermissions(... $permissions)
//    {
//        $this->permissions()->detach();
//        return $this->givePermissionsTo($permissions);
//    }

}