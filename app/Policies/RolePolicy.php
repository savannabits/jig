<?php
namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param    \App\Models\User  $user
     * @return  mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('roles.index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param    \App\Models\User  $user
     * @param    Role  $role
     * @return  mixed
     */
    public function view(User $user, Role $role)
    {
        return $user->hasPermissionTo('roles.show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param    \App\Models\User  $user
     * @return  mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('roles.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param    \App\Models\User  $user
     * @param    Role  $role
     * @return  mixed
     */
    public function update(User $user, Role $role)
    {
        return $user->hasPermissionTo('roles.edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param    \App\Models\User  $user
     * @param    Role  $role
     * @return  mixed
     */
    public function delete(User $user, Role $role)
    {
        return $user->hasPermissionTo('roles.delete') && $role->name !== 'administrator';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param    \App\Models\User  $user
     * @param    Role  $role
     * @return  mixed
     */
    public function restore(User $user, Role $role)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param    \App\Models\User  $user
     * @param    Role  $role
     * @return  mixed
     */
    public function forceDelete(User $user, Role $role)
    {
        return $user->hasPermissionTo('roles.delete') && $role->name !== 'administrator';
    }
}
