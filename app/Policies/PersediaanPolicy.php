<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Persediaan;

use Illuminate\Auth\Access\Response;
use Spatie\Permission\Models\Permission;

class PersediaanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if(Permission::where('name', 'stock: view-any')->count()>0)
            return $user->hasPermissionTo('stock: view-any');
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Persediaan $persediaan): bool
    {
        if(Permission::where('name', 'stock: view')->count()>0)
            return $user->hasPermissionTo('stock: view');
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if(Permission::where('name', 'stock: create')->count()>0)
            return $user->hasPermissionTo('stock: create');
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Persediaan $persediaan): bool
    {
        if(Permission::where('name', 'stock: update')->count()>0)
            return $user->hasPermissionTo('stock: update');
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Persediaan $persediaan): bool
    {
        if(Permission::where('name', 'stock: delete')->count()>0)
            return $user->hasPermissionTo('stock: delete');
        return false;
    }
}
