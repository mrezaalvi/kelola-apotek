<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Kategori;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Permission;

class KategoriPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if(Permission::where('name', 'category: view-any')->count()>0)
            return $user->hasPermissionTo('category: view-any');
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kategori $kategori): bool
    {
        if(Permission::where('name', 'category: view')->count()>0)
            return $user->hasPermissionTo('category: view');
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if(Permission::where('name', 'category: create')->count()>0)
            return $user->hasPermissionTo('category: create');
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kategori $kategori): bool
    {
        if(Permission::where('name', 'category: update')->count()>0)
            return $user->hasPermissionTo('category: update');
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kategori $kategori): bool
    {
        if(Permission::where('name', 'category: delete')->count()>0)
            return $user->hasPermissionTo('category: delete');
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Kategori $kategori): bool
    // {
    //     if(Permission::where('name', 'category: restore')->count()>0)
    //         return $user->hasPermissionTo('category: restore');
    //     return false;
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Kategori $kategori): bool
    // {
    //     if(Permission::where('name', 'category: force-delete')->count()>0)
    //         return $user->hasPermissionTo('category: force-delete');
    //     return false;
    // }
}
