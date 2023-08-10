<?php

namespace App\Observers;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;

class AttachmentObserver
{
    public $afterCommit = true;
    /**
     * Handle the Attachment "created" event.
     */
    public function created(Attachment $attachment): void
    {
        $attachmentId = $attachment->id;
        $mentionUsersIds = $attachment->sendNotificationToTheseUsers;
        if (count($mentionUsersIds) > 0) {
            $mentionUsers = User::whereIn('id', $mentionUsersIds)->get();
            if (count($mentionUsers) > 0) {
                Notification::send(
                    $mentionUsers,
                    NovaNotification::make()
                        ->message('New Attachment Added, please check and add your remarks accordingly.')
                        ->action('Go to Attachment', URL::remote('/zaions/resources/attachments/' . $attachmentId))
                        ->icon('eye')
                        ->type('success')
                );
            }
        }
    }

    /**
     * Handle the Attachment "updated" event.
     */
    public function updated(Attachment $attachment): void
    {
        $attachmentId = $attachment->id;
        $mentionUsersIds = $attachment->sendNotificationToTheseUsers;
        if (count($mentionUsersIds) > 0) {
            $mentionUsers = User::whereIn('id', $mentionUsersIds)->get();
            if (count($mentionUsers) > 0) {
                Notification::send(
                    $mentionUsers,
                    NovaNotification::make()
                        ->message('Attachment Updated, please check and add your remarks accordingly.')
                        ->action('Go to Attachment', URL::remote('/zaions/resources/attachments/' . $attachmentId))
                        ->icon('eye')
                        ->type('warning')
                );
            }
        }
    }

    /**
     * Handle the Attachment "deleted" event.
     */
    public function deleted(Attachment $attachment): void
    {
        //
    }

    /**
     * Handle the Attachment "restored" event.
     */
    public function restored(Attachment $attachment): void
    {
        //
    }

    /**
     * Handle the Attachment "force deleted" event.
     */
    public function forceDeleted(Attachment $attachment): void
    {
        //
    }
}
