<?php

namespace App\Http\Controllers\Zaions\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Project\BoardIdeasResource;
use App\Models\Default\Board;
use App\Models\Default\BoardIdeas;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BoardIdeasController extends Controller
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_boardIdeas->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $itemsCount = BoardIdeas::where('userId', $currentUser->id)->where('boardId', $boardId)->count();
            $items = BoardIdeas::where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => BoardIdeasResource::collection($items),
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_boardIdeas->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'status' => 'nullable|string',
            'internalNotes' => 'nullable|string',
            'image' => 'nullable|json',
            'tags' => 'nullable|json',

            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);


        try {
            $result = BoardIdeas::create([
                'uniqueId' => uniqid(),

                'userId' => $currentUser->id,
                'boardId' => $currentBoard->id,

                'title' => $request->has('title') ? $request->title : null,
                'description' => $request->has('description') ? $request->description : null,
                'status' => $request->has('status') ? $request->status : null,
                'internalNotes' => $request->has('internalNotes') ? $request->internalNotes : null,
                'image' => $request->has('image') ? (is_string($request->image) ? json_decode(
                    $request->image
                ) : $request->image) : null,
                'tags' => $request->has('tags') ? (is_string($request->tags) ? json_decode($request->tags) : $request->tags) : null,

                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive : null,
                'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new BoardIdeasResource($result)
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_boardIdeas->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $item = BoardIdeas::where('uniqueId', $itemId)->where('boardId', $currentBoard->id)->where('userId', $currentUser->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new BoardIdeasResource($item)
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

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_boardIdeas->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'status' => 'nullable|string',
                'internalNotes' => 'nullable|string',
                'tags' => 'nullable|json',
                'image' => 'nullable|json',

                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            $item = BoardIdeas::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->first();

            if ($item) {
                $item->update([
                    'title' => $request->has('title') ? $request->title : $item->title,
                    'description' => $request->has('description') ? $request->description : $item->description,
                    'status' => $request->has('status') ? $request->status : $item->status,
                    'internalNotes' => $request->has('internalNotes') ? $request->internalNotes : $item->internalNotes,
                    'image' => $request->has('image') ? (is_string($request->image) ? json_decode($request->image) : $request->image) : $request->image,
                    'tags' => $request->has('tags') ? (is_string($request->tags) ? json_decode($request->tags) : $request->tags) : $request->tags,

                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->isActive,
                    'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : $request->extraAttributes,
                ]);

                $item = BoardIdeas::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new BoardIdeasResource($item)
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
    public function destroy(Request $request, $boardId, $itemId)
    {
        $currentUser = $request->user();
        $currentBoard = Board::where('uniqueId', $boardId)->first();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_boardIdeas->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


        try {
            $item = BoardIdeas::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->first();

            if ($item) {
                $item->forceDelete();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => ['success' => true]
                ]);
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
