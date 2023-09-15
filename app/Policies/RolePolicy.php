<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if(Permission::where('name', 'role: view-any')->count()>0)
            return $user->hasPermissionTo('role: view-any');
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        if(Permission::where('name', 'role: view')->count()>0)
            return $user->hasPermissionTo('role: view');
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if(Permission::where('name', 'role: create')->count()>0)
            return $user->hasPermissionTo('role: create');
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        if(Permission::where('name', 'role: update')->count()>0)
            return $user->hasPermissionTo('role: update');
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        if(Permission::where('name', 'role: delete')->count()>0)
            return $user->hasPermissionTo('role: delete');
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Role $role): bool
    // {
    //     if(Permission::where('name', 'role: restore')->count()>1)
    //         return $user->hasPermissionTo('role: restore');
    //     return false;
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Role $role): bool
    // {
    //     if(Permission::where('name', 'role: force-delete')->count()>1)
    //         return $user->hasPermissionTo('role: force-delete');
    //     return false;
    // }
}
