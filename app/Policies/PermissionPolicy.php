<?php
namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
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
        return $user->hasPermissionTo('permissions.index');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param    \App\Models\User  $user
     * @param    Permission  $permission
     * @return  mixed
     */
    public function view(User $user, Permission $permission)
    {
        return $user->hasPermissionTo('permissions.show');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param    \App\Models\User  $user
     * @return  mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param    \App\Models\User  $user
     * @param    Permission  $permission
     * @return  mixed
     */
    public function update(User $user, Permission $permission)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param    \App\Models\User  $user
     * @param    Permission  $permission
     * @return  mixed
     */
    public function delete(User $user, Permission $permission)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param    \App\Models\User  $user
     * @param    Permission  $permission
     * @return  mixed
     */
    public function restore(User $user, Permission $permission)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param    \App\Models\User  $user
     * @param    Permission  $permission
     * @return  mixed
     */
    public function forceDelete(User $user, Permission $permission)
    {
        return false;
    }
}
