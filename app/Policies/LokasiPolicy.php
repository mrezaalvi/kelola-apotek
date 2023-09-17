<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Lokasi;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Permission;

class LokasiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if(Permission::where('name', 'location: view-any')->count()>0)
            return $user->hasPermissionTo('location: view-any');
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Lokasi $lokasi): bool
    {
        if(Permission::where('name', 'location: view')->count()>0)
            return $user->hasPermissionTo('location: view');
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if(Permission::where('name', 'location: create')->count()>0)
            return $user->hasPermissionTo('location: create');
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Lokasi $lokasi): bool
    {
        if(Permission::where('name', 'location: update')->count()>0)
            return $user->hasPermissionTo('location: update') && !($lokasi->nama == 'GUDANG UTAMA');
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Lokasi $lokasi): bool
    {
        if(Permission::where('name', 'location: delete')->count()>0)
            return $user->hasPermissionTo('location: delete') && !($lokasi->nama == 'GUDANG UTAMA');
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Lokasi $lokasi): bool
    // {
    //     if(Permission::where('name', 'location: restore')->count()>0)
    //         return $user->hasPermissionTo('location: restore');
    //     return false;
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Lokasi $lokasi): bool
    // {
    //     if(Permission::where('name', 'location: force-delete')->count()>0)
    //         return $user->hasPermissionTo('location: force-delete');
    //     return false;
    // }
}
