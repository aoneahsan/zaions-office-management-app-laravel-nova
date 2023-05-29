<?php

namespace App\Http\Controllers\Zaions\WorkSpace;

use App\Http\Controllers\Controller;
use App\Models\Default\WorkSpace;
use App\Models\ZLink\Analytics\Pixel;
use App\Models\ZLink\Analytics\UtmTag;
use App\Zaions\Enums\ModalsEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;

class WorkspaceModalConnectionsController extends Controller
{
    //
    public function viewAll(Request $request, $workspaceId, $modalType)
    {
        $userId = $request->user()->id;

        if (!$modalType) {
            return ZHelpers::sendBackRequestFailedResponse([
                'item' => ['Modal type is incorrect!']
            ]);
        }

        try {
            $workspace = WorkSpace::where('userId', $userId)->where('uniqueId', $workspaceId)->first();
            switch ($modalType) {
                case ModalsEnum::pixel->name:
                    $pixels = $workspace->pixel;

                    return response()->json([
                        'success' => true,
                        'errors' => [],
                        'message' => 'Request pixels Completed Successfully!',
                        'data' => [
                            'pixels' => $pixels,
                        ],
                        'status' => 200
                    ]);

                case ModalsEnum::UTMTag->name:
                    $UTMTag = $workspace->UTMTag();
                    return response()->json([
                        'success' => true,
                        'errors' => [],
                        'message' => 'Request utm tag Completed Successfully!',
                        'data' => [
                            'items' => $UTMTag
                        ],
                        'status' => 200
                    ]);

                default:
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['Modal Not found!']
                    ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    public function detach(Request $request, $workspaceId, $modalType, $modalId)
    {
        $userId = $request->user()->id;

        if (!$modalId || !$modalType) {
            return ZHelpers::sendBackRequestFailedResponse([
                'item' => ['Modal information is incorrect!']
            ]);
        }

        try {
            $workspace = WorkSpace::where('userId', $userId)->where('uniqueId', $workspaceId)->first();
            switch ($modalType) {
                case ModalsEnum::pixel->name:
                    $pixel = Pixel::where('id', $modalId)->first();

                    if ($pixel) {
                        $pixels = $workspace->pixel()->detach($modalId, ['userId' => $userId]);

                        return response()->json([
                            'success' => true,
                            'errors' => [],
                            'message' => 'Request detach pixels Completed Successfully!',
                            'data' => [
                                'pixels' => $pixels,
                            ],
                            'status' => 200
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([
                            'item' => ['pixel Not found!']
                        ]);
                    }

                case ModalsEnum::UTMTag->name:
                    $UTMTag = UtmTag::where('id', $modalId)->first();

                    if ($UTMTag) {
                        $UTMTag = $workspace->UTMTag()->detach($modalId, ['userId' => $userId]);

                        return response()->json([
                            'success' => true,
                            'errors' => [],
                            'message' => 'Request detach utm tag Completed Successfully!',
                            'data' => [
                                'UTMTag' => $UTMTag,
                            ],
                            'status' => 200
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([
                            'item' => ['UTM Tag Not found!']
                        ]);
                    }

                default:
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['Modal Not found!']
                    ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }


    public function attach(Request $request, $workspaceId, $modalType, $modalId)
    {
        $userId = $request->user()->id;

        if (!$modalId || !$modalType) {
            return ZHelpers::sendBackRequestFailedResponse([
                'item' => ['Modal information is incorrect!']
            ]);
        }

        try {
            $workspace = WorkSpace::where('userId', $userId)->where('uniqueId', $workspaceId)->first();
            switch ($modalType) {
                case ModalsEnum::pixel->name:
                    $pixel = Pixel::where('id', $modalId)->first();

                    if ($pixel) {
                        $pixels = $workspace->pixel()->attach($modalId, ['userId' => $userId]);

                        return response()->json([
                            'success' => true,
                            'errors' => [],
                            'message' => 'Request attach pixels Completed Successfully!',
                            'data' => [
                                'pixels' => $pixels,
                            ],
                            'status' => 200
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([
                            'item' => ['pixel Not found!']
                        ]);
                    }

                case ModalsEnum::UTMTag->name:
                    $UTMTag = UtmTag::where('id', $modalId)->first();

                    if ($UTMTag) {
                        $UTMTag = $workspace->UTMTag()->attach($modalId, ['userId' => $userId]);

                        return response()->json([
                            'success' => true,
                            'errors' => [],
                            'message' => 'Request attach utm tag Completed Successfully!',
                            'data' => [
                                'UTMTag' => $UTMTag,
                            ],
                            'status' => 200
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([
                            'item' => ['UTM Tag Not found!']
                        ]);
                    }

                default:
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['Modal Not found!']
                    ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
