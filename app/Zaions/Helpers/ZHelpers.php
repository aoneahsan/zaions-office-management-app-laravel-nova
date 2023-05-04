<?php

namespace App\Zaions\Helpers;

use App\Zaions\Enums\NamazEnum;
use App\Zaions\Enums\RolesEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;

class ZHelpers
{
  static public function isNRUserSuperAdmin(NovaRequest $request)
  {
    return $request->user()->hasRole(RolesEnum::superAdmin->name);
  }

  static public function getTimezone($request = null)
  {
    if ($request && $request->user() && $request->user()->userTimezone) {
      return $request->user()?->userTimezone;
    } else {
      return config('app.timezone');
    }
  }

  static public function convertTo12Hour(Carbon $time, $request = null)
  {
    // if ($time) {
    //   $hour = null;

    //   if ($time->hour === 0) {
    //     $hour = 12;
    //   } else if ($time->hour <= 12) {
    //     $hour =  $time->hour;
    //   } else if ($time->hour === 13) {
    //     $hour =  1;
    //   } else if ($time->hour === 14) {
    //     $hour =  2;
    //   } else if ($time->hour === 15) {
    //     $hour =  3;
    //   } else if ($time->hour === 16) {
    //     $hour =  4;
    //   } else if ($time->hour === 17) {
    //     $hour =  5;
    //   } else if ($time->hour === 18) {
    //     $hour =  6;
    //   } else if ($time->hour === 19) {
    //     $hour =  7;
    //   } else if ($time->hour === 20) {
    //     $hour =  8;
    //   } else if ($time->hour === 21) {
    //     $hour =  9;
    //   } else if ($time->hour === 22) {
    //     $hour =  10;
    //   } else if ($time->hour === 23) {
    //     $hour =  11;
    //   }

    //   $minute = $time->minute;
    //   $isAm = $hour < 12;
    //   return $hour != null ? array(
    //     'hour' => $hour,
    //     'minute' => $minute,
    //     'isAm' => $isAm
    //   ) : null;
    // } else {
    //   return null;
    // }
    if ($time) {

      $timeStamp = strtotime($time);
      date_default_timezone_set(ZHelpers::getTimezone($request));
      $hour = date('h', $timeStamp);
      $minute = date('i', $timeStamp);
      $isAm = date('A', $timeStamp) === 'AM';
      return array(
        'hour' => intval($hour),
        'minute' => intval($minute),
        'isAm' => $isAm
      );
    } else {
      return null;
    }
  }

  static public function getNamazTimes()
  {
    // the check for min values will be <=, and for max values will be >=
    // // i will subtract 10mins from minutes from namaz max time, so add values accordingly.
    // right now we are only using this function to define the fields for Task, "namazOffered" select field.
    return [
      // fajar time   -   min: 4:10AM  |  max:  6:40AM
      NamazEnum::fajar->name => [
        'min' => [
          'h' => 4,
          'm' => 10
        ],
        'max' => [
          'h' => 6,
          'm' => 40
        ]
      ],
      // zohar time   -   min: 1:10PM  |  max:  3:40PM
      NamazEnum::zohar->name => [
        'min' => [
          'h' => 1,
          'm' => 10
        ],
        'max' => [
          'h' => 3,
          'm' => 40
        ]
      ],
      // asar time   -   min: 5:10PM  |  max:  6:40PM
      NamazEnum::asar->name => [
        'min' => [
          'h' => 5,
          'm' => 10
        ],
        'max' => [
          'h' => 6,
          'm' => 40
        ]
      ],
      // magrib time   -   min: 6:41PM  |  max:  8:10PM
      NamazEnum::magrib->name => [
        'min' => [
          'h' => 6,
          'm' => 41
        ],
        'max' => [
          'h' => 8,
          'm' => 10
        ]
      ],
      // isha time   -   min: 8:10PM  |  max:  1:10AM
      NamazEnum::isha->name => [
        'min' => [
          'h' => 8,
          'm' => 10
        ],
        'max' => [
          'h' => 1,
          'm' => 10
        ]
      ],
      // juma time   -   min: 12:50PM  |  max:  2:40PM
      NamazEnum::juma->name => [
        'min' => [
          'h' => 12,
          'm' => 50
        ],
        'max' => [
          'h' => 2,
          'm' => 40
        ]
      ]
    ];
  }

  static public function getActiveFileDriver()
  {
    return env('FILESYSTEM_DISK', 'public');
  }
}
