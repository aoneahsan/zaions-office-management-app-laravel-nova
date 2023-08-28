<?php

namespace App\Http\Controllers\Zaions;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Zaions\Enums\RolesEnum;
use App\Zaions\Helpers\ZHelpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Laravel\Nova\Notifications\NovaNotification;
use Laravel\Nova\URL;

class TestingController extends Controller
{
    public function zTestingRouteRes(Request $request)
    {
        // Test check if user is super admin
        // $user = $request->user();
        // dd($user->roles()->pluck('name'), $user->hasRole(RolesEnum::superAdmin->name));

        // Test - working with Carbon date and time
        // $carbonNow = Carbon::now($request->user()?->userTimezone);
        // $carbonNow = Carbon::now();
        // $dateInfo = [
        //     '$carbonNow' => $carbonNow,
        //     '$carbonNow->hour' => $carbonNow->hour,
        //     '$carbonNow->minute' => $carbonNow->minute,
        //     '$carbonNow->month' => $carbonNow->month,
        //     '$carbonNow->weekOfYear' => $carbonNow->weekOfYear,
        //     '$carbonNow->day' => $carbonNow->day,
        //     '$carbonNow->dayOfWeek' => $carbonNow->dayOfWeek,
        //     '$carbonNow->dayName' => $carbonNow->dayName,
        //     '12 hour format' => ZHelpers::convertTo12Hour($carbonNow)
        // ];

        // dd($dateInfo, $request->user()?->userTimezone);


        // 10-8-23 - need array with userid as key and user name as value
        // $result = User::where('isActive', true)->pluck('name', 'id');
        // dd($result);

        // send notification to mentioned users
        $notificationSendToPersons = 0;
        $task = Task::where('id', 2)->first();
        // dd($task);
        if ($task) {
            $taskId = $task->id;
            $mentionUsersIdsStr = $task->sendNotificationToTheseUsers;
            if (strlen($mentionUsersIdsStr) > 0) {
                $mentionUsersIdsArr = json_decode($mentionUsersIdsStr);
                if (count($mentionUsersIdsArr) > 0) {
                    $mentionUsersObj = User::whereIn('id', $mentionUsersIdsArr)->get();
                    if (count($mentionUsersObj) > 0) {
                        // dd($mentionUsersObj);
                        $notificationSendToPersons = count($mentionUsersObj);
                        Notification::send(
                            $mentionUsersObj,
                            NovaNotification::make()
                                ->message('Your report is ready to download.')
                                ->action('Go to Task', URL::remote('/zaions/resources/tasks/' . $taskId))
                            ->icon('eye')
                            ->type('info')
                        );
                    }
                }
            }
        }

        dd('notificationSendToPersons', $notificationSendToPersons);


        return response()->json('working fine');
    }
}
