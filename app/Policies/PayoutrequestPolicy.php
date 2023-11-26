<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payoutrequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayoutrequestPolicy
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
        return $user->can('view_any_payoutrequest');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payoutrequest  $payoutrequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Payoutrequest $payoutrequest)
    {
        return $user->can('view_payoutrequest');
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
     * @param  \App\Models\Payoutrequest  $payoutrequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Payoutrequest $payoutrequest)
    {
        return $user->can('update_payoutrequest');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payoutrequest  $payoutrequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Payoutrequest $payoutrequest)
    {
        return $user->can('delete_payoutrequest');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(User $user)
    {
        return $user->can('delete_any_payoutrequest');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payoutrequest  $payoutrequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Payoutrequest $payoutrequest)
    {
        return $user->can('force_delete_payoutrequest');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDeleteAny(User $user)
    {
        return $user->can('force_delete_any_payoutrequest');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payoutrequest  $payoutrequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Payoutrequest $payoutrequest)
    {
        return $user->can('restore_payoutrequest');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restoreAny(User $user)
    {
        return $user->can('restore_any_payoutrequest');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payoutrequest  $payoutrequest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function replicate(User $user, Payoutrequest $payoutrequest)
    {
        return $user->can('replicate_payoutrequest');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reorder(User $user)
    {
        return $user->can('reorder_payoutrequest');
    }

}
