<?php

namespace App\Zaions\Helpers;

use App\Models\User;
use App\Zaions\Enums\RolesEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ZHelpers
{
  static public function isNRUserSuperAdmin(Request $request): bool
  {
    return ZHelpers::isSuperAdmin($request->user());
  }

  static public function isAdminLevelUserOrOwner(User $user, $modelOwnerId): bool
  {
    return ZHelpers::isAdminLevelUser($user) || $user->id === $modelOwnerId;
  }

  static public function isAdminLevelUser(User $user): bool
  {
    return ZHelpers::isSuperAdmin($user) || ZHelpers::isAdmin($user);
  }

  static public function isSuperAdmin(User $user): bool
  {
    if ($user) {
      return $user->hasRole(RolesEnum::superAdmin->name);
    } else {
      return false;
    }
  }

  static public function isAdmin(User $user): bool
  {
    if ($user) {
      return $user->hasRole(RolesEnum::admin->name);
    } else {
      return false;
    }
  }

  static public function getTimezone($request = null): string
  {
    if ($request && $request->user() && $request->user()->userTimezone) {
      return $request->user()?->userTimezone;
    } else {
      return config('app.timezone');
    }
  }

  static public function convertTo12Hour(Carbon $time, $request = null)
  {
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

  static public function getActiveFileDriver(): string
  {
    return env('FILESYSTEM_DISK', 'public');
  }


  // Copied from ZaionsHelpers - ZLink Laravel Project

  public static function ZaionsRoleName()
  {
    return [
      'admin' => 'Admin',
      'shop_manager' => 'Shop Manager',
      'creator' => 'Creator',
      'viewer' => 'Viewer',
    ];
  }

  public function is_admin($user)
  {
    $role = $this->ZaionsRoleName()['admin'];
    if ($user->hasRole($role)) {
      return true;
    }

    return false;
  }

  public static function sendBackRequestFailedResponse($errors)
  {
    return response()->json([
      'errors' => $errors,
      'data' => [],
      'success' => false,
      'status' => 500,
      'message' => 'Error Occurred, try again later.'
    ], 500);
  }

  public static function sendBackInvalidParamsResponse($errors)
  {
    return response()->json([
      'errors' => $errors,
      'data' => [],
      'success' => false,
      'status' => 400,
      'message' => 'Invalid params send, please send all required request params.'
    ], 500);
  }

  public static function sendBackRequestCompletedResponse($data)
  {
    return response()->json([
      'errors' => [],
      'data' => $data,
      'success' => true,
      'status' => 200,
      'message' => 'Request completed successfully.'
    ], 200);
  }

  // send back server error response
  public static function sendBackServerErrorResponse(\Throwable $th)
  {
    return response()->json([
      'errors' => [
        'error' => [$th->getMessage()]
      ],
      'data' => [],
      'success' => false,
      'status' => 500,
      'message' => 'Error Occurred, try again later.'
    ], 500);
  }

  // check if file exists
  public static function checkIfFileExists($filePath)
  {
    return $filePath && Storage::exists($filePath);
  }

  // get full file url
  public static function getFullFileUrl($filePath): string | null
  {
    if (ZHelpers::checkIfFileExists($filePath)) {
      $fileUrl = Storage::url($filePath);

      $appUrl = env('FILESYSTEM_ROOT_URL', 'https://app-backend.zaions.com/public');

      return $appUrl . $fileUrl;
      // return $appUrl;
      // return $fileUrl;
    } else {
      return null;
    }
  }

  // store file & return file path
  public static function storeFile(Request $request, $fileKey, $fileStorePath = 'uploaded-files')
  {
    if ($request->file($fileKey)) {
      $filePath = Storage::putFile($fileStorePath, $request->file($fileKey), 'public');

      $appUrl = env('FILESYSTEM_ROOT_URL', 'https://app-backend.zaions.com/public');

      return [
        'fileUrl' => $appUrl . '/' . $filePath,
        'filePath' => $filePath,
      ];
    } else {
      return null;
    }
  }

  // store file & return file path
  public static function storeFiles($files, $fileStorePath = 'uploaded-files')
  {
    if ($files) {
      $filesData = [];
      foreach ($files as $file) {
        $filePath = Storage::putFile($fileStorePath, $file, 'public');

        $appUrl = env('FILESYSTEM_ROOT_URL', 'https://app-backend.zaions.com/public');

        $filesData[] = [
          'fileUrl' => $appUrl . '/' . $filePath,
          'filePath' => $filePath,
        ];
      }
      return $filesData;
    } else {
      return null;
    }
  }

  // delete file if exists
  public static function deleteFile($filePath)
  {
    if ($filePath && ZHelpers::checkIfFileExists($filePath)) {
      $deleted = Storage::delete($filePath);

      return $deleted;
    } else {
      return false;
    }
  }

  public static function getThirdPartyApiRequestDetails()
  {
    return [
      'limit' => 20,
      'offset' => 0,
      'giphyApiKey' => 'e2ch1pYIBKfkBbEKy5eaCWTAGA8QyUx1'
    ];
  }

  public static function zJsonDecode($value)
  {
    try {
      if ($value && is_string($value)) {
        return json_decode($value);
      } else if (!$value) {
        return null;
      } else {
        return $value;
      }
    } catch (\Throwable $th) {
      return null;
    }
  }

  public static function getUniqueReferralCode(): string
  {
    // Generate a unique referral code
    $referralCode = Str::random(8); // Generate an 8-character random string

    // Check if the generated referral code already exists in the database
    $isUnique = !User::where('referralCode', $referralCode)->exists();

    // If the generated referral code is not unique, generate a new one until it is unique
    while (!$isUnique) {
      $referralCode = Str::random(8);
      $isUnique = !User::where('referralCode', $referralCode)->exists();
    }

    return $referralCode;
  }
}
