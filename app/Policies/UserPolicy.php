<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Permission;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if(Permission::where('name', 'user: view-any')->count()>0)
            return $user->hasPermissionTo('user: view-any');
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if(Permission::where('name', 'user: view')->count()>0)
            return $user->hasPermissionTo('user: view');
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if(Permission::where('name', 'user: create')->count()>0)
            return $user->hasPermissionTo('user: create');
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if(Permission::where('name', 'user: update')->count()>0)
            return $user->hasPermissionTo('user: update');
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if(Permission::where('name', 'user: delete')->count()>0)
            return $user->hasPermissionTo('user: delete');
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        if(Permission::where('name', 'user: restore')->count()>0)
            return $user->hasPermissionTo('user: restore');
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        if(Permission::where('name', 'user: force-delete')->count()>0)
            return $user->hasPermissionTo('user: force-delete');
        return false;
    }
}
