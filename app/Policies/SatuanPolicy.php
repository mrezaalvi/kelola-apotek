<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Satuan;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Permission;

class SatuanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if(Permission::where('name', 'unit: view-any')->count()>0)
            return $user->hasPermissionTo('unit: view-any');
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Satuan $satuan): bool
    {
        if(Permission::where('name', 'unit: view')->count()>0)
            return $user->hasPermissionTo('unit: view');
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if(Permission::where('name', 'unit: create')->count()>0)
            return $user->hasPermissionTo('unit: create');
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Satuan $satuan): bool
    {
        if(Permission::where('name', 'unit: update')->count()>0)
            return $user->hasPermissionTo('unit: update');
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Satuan $satuan): bool
    {
        if(Permission::where('name', 'unit: delete')->count()>0)
            return $user->hasPermissionTo('unit: delete');
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Satuan $satuan): bool
    // {
    //     if(Permission::where('name', 'unit: retore')->count()>0)
    //         return $user->hasPermissionTo('unit: restore');
    //     return false;
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Satuan $satuan): bool
    // {
    //     if(Permission::where('name', 'unit: force-delete')->count()>0)
    //         return $user->hasPermissionTo('unit: force-delete');
    //     return false;
    // }
}
