<?php

namespace App\Observers;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;

class ReplyObserver
{
    /**
     * Handle the Reply "created" event.
     */
    public function created(Reply $reply): void
    {
        $replyId = $reply->id;
        $mentionUsersIds = $reply->sendNotificationToTheseUsers;
        if (count($mentionUsersIds) > 0) {
            $mentionUsers = User::whereIn('id', $mentionUsersIds)->get();
            if (count($mentionUsers) > 0) {
                Notification::send(
                    $mentionUsers,
                    NovaNotification::make()
                        ->message('New reply added, please check and add your remarks accordingly.')
                        ->action('Go to Reply', URL::remote('/zaions/resources/replies/' . $replyId))
                        ->icon('eye')
                        ->type('success')
                );
            }
        }
    }

    /**
     * Handle the Reply "updated" event.
     */
    public function updated(Reply $reply): void
    {
        $replyId = $reply->id;
        $mentionUsersIds = $reply->sendNotificationToTheseUsers;
        if (count($mentionUsersIds) > 0) {
            $mentionUsers = User::whereIn('id', $mentionUsersIds)->get();
            if (count($mentionUsers) > 0) {
                Notification::send(
                    $mentionUsers,
                    NovaNotification::make()
                        ->message('Reply updated, please check and add your remarks accordingly.')
                        ->action('Go to Reply', URL::remote('/zaions/resources/replies/' . $replyId))
                        ->icon('eye')
                        ->type('warning')
                );
            }
        }
    }

    /**
     * Handle the Reply "deleted" event.
     */
    public function deleted(Reply $reply): void
    {
        //
    }

    /**
     * Handle the Reply "restored" event.
     */
    public function restored(Reply $reply): void
    {
        //
    }

    /**
     * Handle the Reply "force deleted" event.
     */
    public function forceDeleted(Reply $reply): void
    {
        //
    }
}
