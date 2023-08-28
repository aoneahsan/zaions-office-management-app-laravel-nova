<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;

class TaskObserver
{

    public $afterCommit = true;

    // /**
    //  * Handle the Task "created" event.
    //  */
    public function created(Task $task): void
    {
        $taskId = $task->id;
        $currentUser = Auth::user();
        if ($currentUser->id != $task->assignedTo) {
            $assignedTo = $task->assignedTo;
            $assignedUser = User::where('id', $assignedTo)->first();
            if ($assignedUser) {
                $assignedUser->notify(
                    NovaNotification::make()
                        ->message('New Task assigned, please have a look')
                        ->action('Go to Task', URL::make('/resources/tasks/' . $taskId))
                        ->icon('eye')
                        ->type('success')
                );
            }
        }

        // Now send notification to all users in mentioned array
        $taskId = $task->id;
        $mentionUsersIds = $task->sendNotificationToTheseUsers;
        if (count($mentionUsersIds) > 0) {
            $mentionUsers = User::whereIn('id', $mentionUsersIds)->get();
            if (count($mentionUsers) > 0) {
                Notification::send(
                    $mentionUsers,
                    NovaNotification::make()
                        ->message('Task Updated, please check the task details and add your remarks accordingly.')
                        ->action('Go to Task', URL::remote('/zaions/resources/tasks/' . $taskId))
                        ->icon('eye')
                        ->type('success')
                );
            }
        }
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {

        $taskId = $task->id;
        $currentUser = Auth::user();
        if ($currentUser->id !== $task->assignedTo) {
            $assignedTo = $task->assignedTo;
            $assignedUser = User::where('id', $assignedTo)->first();
            if ($assignedUser) {
                $assignedUser->notify(
                    NovaNotification::make()
                        ->message('New Task assigned, please have a look')
                        ->action('Go to Task', URL::make('/resources/tasks/' . $taskId))
                        ->icon('eye')
                        ->type('warning')
                );
            }
        }

        // Now send notification to all users in mentioned array
        $taskId = $task->id;
        $mentionUsersIds = $task->sendNotificationToTheseUsers;
        if (count($mentionUsersIds) > 0) {
            $mentionUsers = User::whereIn('id', $mentionUsersIds)->get();
            if (count($mentionUsers) > 0) {
                Notification::send(
                    $mentionUsers,
                    NovaNotification::make()
                        ->message('Task Updated, please check the task details and add your remarks accordingly.')
                        ->action('Go to Task', URL::remote('/zaions/resources/tasks/' . $taskId))
                        ->icon('eye')
                        ->type('warning')
                );
            }
        }
    }
}
