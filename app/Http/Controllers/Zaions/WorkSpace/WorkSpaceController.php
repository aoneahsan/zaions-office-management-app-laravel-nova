<?php

namespace App\Http\Controllers\Zaions\WorkSpace;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\WorkSpace\WorkSpaceResource;
use App\Models\Default\WorkSpace;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;

class WorkSpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        try {
            $itemsCount = WorkSpace::where('userId', $userId)->count();
            $items = WorkSpace::where('userId', $userId)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => WorkSpaceResource::collection($items),
                    'itemsCount' => $itemsCount
                ],
                'status' => 200
            ]);
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'timezone' => 'required|string',
            'workspaceData' => 'nullable|json',

            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        $userId = $request->user()->id;

        $workspaceData = $request->has('workspaceData') ? (is_string($request->workspaceData) ? json_decode($request->workspaceData) : $request->workspaceData) : null;
        $extraAttributes = $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : null;
        try {
            $result = WorkSpace::create([
                'uniqueId' => uniqid(),

                'userId' => $userId,
                'title' => $request->has('title') ? $request->title : null,
                'timezone' => $request->has('timezone') ? $request->timezone : null,
                'workspaceData' => $workspaceData,

                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive : null,
                'extraAttributes' => $extraAttributes,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new WorkSpaceResource($result)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $itemId)
    {
        $userId = $request->user()->id;
        try {
            $item = WorkSpace::where('uniqueId', $itemId)->where('userId', $userId)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new WorkSpaceResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'title' => 'required|string',
            'timezone' => 'required|string',
            'workspaceData' => 'nullable|json',

            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        $userId = $request->user()->id;
        $workspaceData = $request->has('workspaceData') ? (is_string($request->workspaceData) ? json_decode($request->workspaceData) : $request->workspaceData) : $request->workspaceData;
        $extraAttributes = $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : $request->extraAttributes;
        try {
            $item = WorkSpace::where('uniqueId', $itemId)->where('userId', $userId)->first();

            if ($item) {
                $item->update([
                    'title' => $request->has('title') ? $request->title : $item->workspaceName,
                    'timezone' => $request->has('timezone') ? $request->timezone : $item->timezone,
                    'workspaceData' => $workspaceData,


                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->isActive,
                    'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                    'extraAttributes' => $extraAttributes,
                ]);

                $item = WorkSpace::where('uniqueId', $itemId)->where('userId', $userId)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new WorkSpaceResource($item)
                ]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $itemId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $itemId)
    {
        $userId = $request->user()->id;
        try {
            $item = WorkSpace::where('uniqueId', $itemId)->where('userId', $userId)->first();

            if ($item) {
                $item->forceDelete();
                return ZHelpers::sendBackRequestCompletedResponse([]);
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['Not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
