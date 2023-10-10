<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Supplier;
use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Permission;

class SupplierPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if(Permission::where('name', 'supplier: view-any')->exists())
            return $user->hasPermissionTo('supplier: view-any');
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Supplier $supplier): bool
    {
        if(Permission::where('name', 'supplier: view')->exists())
            return $user->hasPermissionTo('supplier: view');
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if(Permission::where('name', 'supplier: create')->exists())
            return $user->hasPermissionTo('supplier: create');
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Supplier $supplier): bool
    {
        if(Permission::where('name', 'supplier: update')->exists())
            return $user->hasPermissionTo('supplier: update');
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Supplier $supplier): bool
    {
        if(Permission::where('name', 'supplier: delete')->exists())
            return $user->hasPermissionTo('supplier: delete');
        return false;
    }
}
