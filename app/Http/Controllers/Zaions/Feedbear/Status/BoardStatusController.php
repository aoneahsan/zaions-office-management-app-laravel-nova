<?php

namespace App\Http\Controllers\Zaions\Feedbear\Status;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Feedbear\Status\BoardStatusResource;
use App\Models\Default\Board;
use App\Models\Feedbear\status\BoardStatus;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use Illuminate\Http\Request;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Support\Facades\Gate;


class BoardStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $boardId)
    {
        $currentUser = $request->user();
        $currentBoard = Board::where('uniqueId', $boardId)->first();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_boardStatus->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $itemsCount = BoardStatus::where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->count();
            $items = BoardStatus::where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => BoardStatusResource::collection($items),
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
    public function store(Request $request, $boardId)
    {
        $currentUser = $request->user();
        $currentBoard = Board::where('uniqueId', $boardId)->first();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_boardStatus->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'color' => 'nullable|string',
            // 'isDefault' => 'nullable|boolean',
            // 'isEditable' => 'nullable|boolean',
            // 'isDeletable' => 'nullable|boolean',

            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);


        try {
            $result = BoardStatus::create([
                'uniqueId' => uniqid(),

                'userId' => $currentUser->id,
                'boardId' => $currentBoard->id,

                'title' => $request->has('title') ? $request->title : null,
                'description' => $request->has('description') ? $request->description : null,
                'color' => $request->has('color') ? $request->color : null,
                'isDefault' => false,
                'isEditable' => true,
                'isDeletable' => true,

                'isActive' => $request->has('isActive') ? $request->isActive : false,
                'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new BoardStatusResource($result)
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
    public function show(Request $request, $boardId, $itemId)
    {
        $currentUser = $request->user();

        $currentBoard = Board::where('uniqueId', $boardId)->first();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_boardStatus->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $item = BoardStatus::where('uniqueId', $itemId)->where('boardId', $currentBoard->id)->where('userId', $currentUser->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new BoardStatusResource($item)
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
    public function update(Request $request, $boardId, $itemId)
    {
        try {
            $currentUser = $request->user();
            $currentBoard = Board::where('uniqueId', $boardId)->first();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_boardStatus->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'title' => 'required|string',
                'description' => 'nullable|string',
                'color' => 'nullable|string',
                // 'isDefault' => 'nullable|boolean',
                // 'isEditable' => 'nullable|boolean',
                // 'isDeletable' => 'nullable|boolean',

                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            $item = BoardStatus::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->first();

            if ($item) {
                if ($item->isEditable) {
                    $item->update([
                        'title' => $request->has('title') ? $request->title : $item->title,
                        'description' => $request->has('description') ? $request->description : $item->description,
                        'color' => $request->has('color') ? $request->color : $item->color,
                        'isDefault' => $item->isDefault,
                        'isEditable' => $item->isEditable,
                        'isDeletable' => $item->isDeletable,

                        'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                        'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : $request->extraAttributes,
                    ]);

                    $item = BoardStatus::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->first();

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new BoardStatusResource($item)
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['Board status is not editable!']
                    ]);
                }
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
    public function destroy(Request $request, $boardId, $itemId)
    {
        $currentUser = $request->user();
        $currentBoard = Board::where('uniqueId', $boardId)->first();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_boardStatus->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


        try {
            $item = BoardStatus::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->first();

            if ($item) {
                if ($item->isDeletable) {
                    $item->forceDelete();
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => ['success' => true]
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['Board status is not deletable!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['success' => true, 'message' => 'Not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
