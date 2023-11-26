<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Refferal;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefferalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('view_any_refferal');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Refferal  $refferal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Refferal $refferal)
    {
        return $user->can('view_refferal');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Refferal  $refferal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Refferal $refferal)
    {
        return $user->can('update_refferal');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Refferal  $refferal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Refferal $refferal)
    {
        return $user->can('delete_refferal');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(User $user)
    {
        return $user->can('delete_any_refferal');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Refferal  $refferal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Refferal $refferal)
    {
        return $user->can('force_delete_refferal');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDeleteAny(User $user)
    {
        return $user->can('force_delete_any_refferal');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Refferal  $refferal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Refferal $refferal)
    {
        return $user->can('restore_refferal');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restoreAny(User $user)
    {
        return $user->can('restore_any_refferal');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Refferal  $refferal
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function replicate(User $user, Refferal $refferal)
    {
        return $user->can('replicate_refferal');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reorder(User $user)
    {
        return $user->can('reorder_refferal');
    }

}
