<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NotificationTemplate;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationTemplatePolicy
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
        return $user->can('view_any_notification::template');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NotificationTemplate  $notificationTemplate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, NotificationTemplate $notificationTemplate)
    {
        return $user->can('view_notification::template');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create_notification::template');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NotificationTemplate  $notificationTemplate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, NotificationTemplate $notificationTemplate)
    {
        return $user->can('update_notification::template');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NotificationTemplate  $notificationTemplate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, NotificationTemplate $notificationTemplate)
    {
        return $user->can('delete_notification::template');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAny(User $user)
    {
        return $user->can('delete_any_notification::template');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NotificationTemplate  $notificationTemplate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, NotificationTemplate $notificationTemplate)
    {
        return $user->can('force_delete_notification::template');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDeleteAny(User $user)
    {
        return $user->can('force_delete_any_notification::template');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NotificationTemplate  $notificationTemplate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, NotificationTemplate $notificationTemplate)
    {
        return $user->can('restore_notification::template');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restoreAny(User $user)
    {
        return $user->can('restore_any_notification::template');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NotificationTemplate  $notificationTemplate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function replicate(User $user, NotificationTemplate $notificationTemplate)
    {
        return $user->can('replicate_notification::template');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reorder(User $user)
    {
        return $user->can('reorder_notification::template');
    }

}
