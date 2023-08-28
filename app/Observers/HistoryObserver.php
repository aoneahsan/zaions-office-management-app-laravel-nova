<?php

namespace App\Observers;

use App\Models\History;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;

class HistoryObserver
{
    /**
     * Handle the History "created" event.
     */
    public function created(History $history): void
    {
        $historyId = $history->id;
        $mentionUsersIds = $history->sendNotificationToTheseUsers;
        if (count($mentionUsersIds) > 0) {
            $mentionUsers = User::whereIn('id', $mentionUsersIds)->get();
            if (count($mentionUsers) > 0) {
                Notification::send(
                    $mentionUsers,
                    NovaNotification::make()
                        ->message('New task history record added, please check and add your remarks accordingly.')
                        ->action('Go to History', URL::remote('/zaions/resources/histories/' . $historyId))
                        ->icon('eye')
                        ->type('success')
                );
            }
        }
    }

    /**
     * Handle the History "updated" event.
     */
    public function updated(History $history): void
    {
        $historyId = $history->id;
        $mentionUsersIds = $history->sendNotificationToTheseUsers;
        if (count($mentionUsersIds) > 0) {
            $mentionUsers = User::whereIn('id', $mentionUsersIds)->get();
            if (count($mentionUsers) > 0) {
                Notification::send(
                    $mentionUsers,
                    NovaNotification::make()
                        ->message('Task history record updated, please check and add your remarks accordingly.')
                        ->action('Go to History', URL::remote('/zaions/resources/histories/' . $historyId))
                        ->icon('eye')
                        ->type('warning')
                );
            }
        }
    }

    /**
     * Handle the History "deleted" event.
     */
    public function deleted(History $history): void
    {
        //
    }

    /**
     * Handle the History "restored" event.
     */
    public function restored(History $history): void
    {
        //
    }

    /**
     * Handle the History "force deleted" event.
     */
    public function forceDeleted(History $history): void
    {
        //
    }
}
