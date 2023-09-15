<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Produk;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Permission;

class ProdukPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if(Permission::where('name', 'product: view-any')->count()>0)
            return $user->hasPermissionTo('product: view-any');
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Produk $produk): bool
    {
        if(Permission::where('name', 'product: view')->count()>0)
            return $user->hasPermissionTo('product: view');
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if(Permission::where('name', 'product: create')->count()>0)
            return $user->hasPermissionTo('product: create');
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Produk $produk): bool
    {
        if(Permission::where('name', 'product: update')->count()>0)
            return $user->hasPermissionTo('product: update');
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Produk $produk): bool
    {
        if(Permission::where('name', 'product: delete')->count()>0)
            return $user->hasPermissionTo('product: delete');
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Produk $produk): bool
    {
        if(Permission::where('name', 'product: restore')->count()>0)
            return $user->hasPermissionTo('product: restore');
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Produk $produk): bool
    {
        if(Permission::where('name', 'product: force-delete')->count()>0)
            return $user->hasPermissionTo('product: force-delete');
        return false;
    }
}
