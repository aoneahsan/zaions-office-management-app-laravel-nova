<?php

namespace App\Observers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        $commentId = $comment->id;
        $mentionUsersIds = $comment->sendNotificationToTheseUsers;
        if (count($mentionUsersIds) > 0) {
            $mentionUsers = User::whereIn('id', $mentionUsersIds)->get();
            if (count($mentionUsers) > 0) {
                Notification::send(
                    $mentionUsers,
                    NovaNotification::make()
                        ->message('New comment added, please check and add your remarks accordingly.')
                        ->action('Go to Comment', URL::remote('/zaions/resources/comments/' . $commentId))
                        ->icon('eye')
                        ->type('success')
                );
            }
        }
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        $commentId = $comment->id;
        $mentionUsersIds = $comment->sendNotificationToTheseUsers;
        if (count($mentionUsersIds) > 0) {
            $mentionUsers = User::whereIn('id', $mentionUsersIds)->get();
            if (count($mentionUsers) > 0) {
                Notification::send(
                    $mentionUsers,
                    NovaNotification::make()
                        ->message('Comment updated, please check and add your remarks accordingly.')
                        ->action('Go to Comment', URL::remote('/zaions/resources/comments/' . $commentId))
                        ->icon('eye')
                        ->type('warning')
                );
            }
        }
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }
}
