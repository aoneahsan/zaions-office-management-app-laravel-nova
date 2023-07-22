<?php

namespace App\Http\Controllers\Zaions\Feedbear\Board;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Default\CommentResource;
use App\Http\Resources\Zaions\Feedbear\Board\BoardIdeasResource;
use App\Models\Default\Comment;
use App\Models\Feedbear\Board\Board;
use App\Models\Feedbear\Board\BoardIdeas;
use App\Models\Feedbear\status\BoardStatus;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use Illuminate\Http\Request;
use App\Zaions\Helpers\ZHelpers;
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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_boardIdeas->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $currentBoard = Board::where('uniqueId', $boardId)->first();

        if (!$currentBoard) {
            return ZHelpers::sendBackNotFoundResponse();
        } else {
            try {
                $itemsCount = BoardIdeas::where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->count();
                $items = BoardIdeas::where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->withCount('votes')->get();
                // $idea = BoardIdeas::where('uniqueId', '64baa8af5f69e')->first();
                // $votes = BoardIdeaVotes::where('boardIdeaId', $idea->id)->count();

                return response()->json([
                    'success' => true,
                    'errors' => [],
                    'message' => 'Request Completed Successfully!',
                    'data' => [
                        'items' => BoardIdeasResource::collection($items),
                        'itemsCount' => $itemsCount,
                        // 'votes' => $votes,
                        // '$idea' => $idea
                    ],
                    'status' => 200
                ]);
            } catch (\Throwable $th) {
                return ZHelpers::sendBackServerErrorResponse($th);
            }
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

        $statusId = null;

        // userGeneratedStatusId = true/false
        if ($request->has('statusUniqueId') && $request->statusUniqueId !== null) {
            $currentStatus = BoardStatus::where('uniqueId', $request->statusUniqueId)->first();

            $statusId = $currentStatus->uniqueId;
        }


        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'statusUniqueId' => 'nullable|string',
            'internalNotes' => 'nullable|string',
            'image' => 'nullable|json',
            'tags' => 'nullable|json',

            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'isCompleted' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);


        try {
            $result = BoardIdeas::create([
                'uniqueId' => uniqid(),

                'userId' => $currentUser->id,
                'boardId' => $currentBoard->id,
                'statusUniqueId' => $statusId,

                'title' => $request->has('title') ? $request->title : null,
                'description' => $request->has('description') ? $request->description : null,
                // 'status' => $request->has('status') ? $request->status : null,
                'internalNotes' => $request->has('internalNotes') ? $request->internalNotes : null,
                'image' => $request->has('image') ? (is_string($request->image) ? json_decode(
                    $request->image
                ) : $request->image) : null,
                'tags' => $request->has('tags') ? (is_string($request->tags) ? json_decode($request->tags) : $request->tags) : null,

                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isCompleted' =>  $request->has('isCompleted') ? $request->isCompleted : false,
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
            // userGeneratedStatusId = true/false
            $statusId = null;

            // userGeneratedStatusId = true/false
            if ($request->has('statusUniqueId') && $request->statusUniqueId !== null) {
                $currentStatus = BoardStatus::where('uniqueId', $request->statusUniqueId)->first();

                $statusId = $currentStatus->uniqueId;
            }


            $request->validate([
                'title' => 'required|string|max:200',
                'description' => 'required|string|max:1500',
                'status' => 'nullable|string',
                'internalNotes' => 'nullable|string|max:1500',
                'tags' => 'nullable|json',
                'image' => 'nullable|json',
                'isCompleted' => 'nullable|boolean',

                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            $item = BoardIdeas::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->first();

            if ($item) {
                $item->update([
                    'statusUniqueId' => $statusId,
                    'title' => $request->has('title') ? $request->title : $item->title,
                    'description' => $request->has('description') ? $request->description : $item->description,
                    'status' => $request->has('status') ? $request->status : $item->status,
                    'internalNotes' => $request->has('internalNotes') ? $request->internalNotes : $item->internalNotes,
                    'image' => $request->has('image') ? (is_string($request->image) ? json_decode($request->image) : $request->image) : $request->image,
                    'tags' => $request->has('tags') ? (is_string($request->tags) ? json_decode($request->tags) : $request->tags) : $request->tags,
                    'isCompleted' => $request->has('isCompleted') ? $request->isCompleted : $item->isCompleted,

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

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_boardIdeas->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $currentBoard = Board::where('uniqueId', $boardId)->first();

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

    /**
     * Display a listing of the resource comments.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewComments(Request $request, $boardId, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_comment->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $currentBoard = Board::where('userId', $currentUser->id)->where('uniqueId', $boardId)->first();

            if ($currentBoard) {
                $item = BoardIdeas::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->first();

                if ($item) {
                    $commentable_type = 'App\\Models\\Feedbear\\Board\\BoardIdeas';


                    $itemsCount = Comment::where('userId', $currentUser->id)->where('commentable_type', $commentable_type)->where('commentable_id', $item->id)->count();

                    $items = Comment::where('userId', $currentUser->id)->where('commentable_type', $commentable_type)->where('commentable_id', $item->id)->with('user')->with('replies')->get();

                    return response()->json([
                        'success' => true,
                        'errors' => [],
                        'message' => 'Request Completed Successfully!',
                        'data' => [
                            'items' => CommentResource::collection($items),
                            'itemsCount' => $itemsCount,
                        ],
                        'status' => 200
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['success' => true, 'message' => 'Idea Not found!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['success' => true, 'message' => 'Board Not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Store a newly created resource comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComment(Request $request, $boardId, $itemId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_comment->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $currentBoard = Board::where('userId', $currentUser->id)->where('uniqueId', $boardId)->first();

            if ($currentBoard) {
                $item = BoardIdeas::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->first();


                if ($item) {
                    $request->validate([
                        'content' => 'required|string',

                        'sortOrderNo' => 'nullable|integer',
                        'isActive' => 'nullable|boolean',
                        'extraAttributes' => 'nullable|json',
                    ]);

                    $commentable_type = 'App\\Models\\Feedbear\\Board\\BoardIdeas';

                    $result = Comment::create([
                        'uniqueId' => uniqid(),

                        'userId' => $currentUser->id,
                        'commentable_type' => $commentable_type,
                        'commentable_id' => $item->id,

                        'content' => $request->has('content') ? $request->content : null,

                        'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                        'isActive' => $request->has('isActive') ? $request->isActive : true,
                        'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : null,
                    ]);

                    if ($result) {
                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => new CommentResource($result)
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([]);
                    }
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['success' => true, 'message' => 'Idea Not found!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['success' => true, 'message' => 'Board Not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }

    /**
     * Remove a newly created resource comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyComment(Request $request, $boardId, $itemId, $commentId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_comment->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $currentBoard = Board::where('userId', $currentUser->id)->where('uniqueId', $boardId)->first();

            if ($currentBoard) {
                $item = BoardIdeas::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->first();


                if ($item) {
                    $commentable_type = 'App\\Models\\Feedbear\\Board\\BoardIdeas';

                    $comment = Comment::where('userId', $currentUser->id)->where('commentable_type', $commentable_type)->where('commentable_id', $item->id)->where('uniqueId', $commentId)->with('user')->first();

                    if ($comment) {
                        $comment->forceDelete();
                        return ZHelpers::sendBackRequestCompletedResponse([
                            'item' => ['success' => true]
                        ]);
                    } else {
                        return ZHelpers::sendBackRequestFailedResponse([
                            'item' => ['success' => true, 'message' => 'Comment Not found!']
                        ]);
                    }
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['success' => true, 'message' => 'Idea Not found!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['success' => true, 'message' => 'Board Not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
