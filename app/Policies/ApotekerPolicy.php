<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Apoteker;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Permission;

class ApotekerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if(Permission::where('name', 'apoteker: view-any')->exists())
            return $user->hasPermissionTo('apoteker: view-any');
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Apoteker $apoteker): bool
    {
        if(Permission::where('name', 'apoteker: view')->exists())
            return $user->hasPermissionTo('apoteker: view');
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if(Permission::where('name', 'apoteker: create')->exists())
            return $user->hasPermissionTo('apoteker: create');
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Apoteker $apoteker): bool
    {
        if(Permission::where('name', 'apoteker: update')->exists())
            return $user->hasPermissionTo('apoteker: update');
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Apoteker $apoteker): bool
    {
        if(Permission::where('name', 'apoteker: delete')->exists())
            return $user->hasPermissionTo('apoteker: delete');
        return false;
    }
}
