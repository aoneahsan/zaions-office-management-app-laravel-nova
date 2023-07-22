<?php

namespace App\Http\Controllers\Zaions\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Default\ReplyResource;
use App\Models\Default\Comment;
use App\Models\Default\Reply;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use Illuminate\Http\Request;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $commentId)
    {
        $currentUser = $request->user();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_reply->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $currentComment = Comment::where('userId', $currentUser->id)->where('uniqueId', $commentId)->first();

        if (!$currentComment) {
            return ZHelpers::sendBackNotFoundResponse();
        } else {
            try {
                $itemsCount = Reply::where('userId', $currentUser->id)->where('commentId', $currentComment->id)->with('user')->count();

                $items = Reply::where('userId', $currentUser->id)->where('commentId', $currentComment->id)->with('user')->get();

                if ($items) {
                    return response()->json([
                        'success' => true,
                        'errors' => [],
                        'message' => 'Request Completed Successfully!',
                        'data' => [
                            'items' => ReplyResource::collection($items),
                            'itemsCount' => $itemsCount,
                        ],
                        'status' => 200
                    ]);
                }
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
    public function store(Request $request, $commentId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_reply->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $currentComment = Comment::where('userId', $currentUser->id)->where('uniqueId', $commentId)->first();

            if ($currentComment) {
                $request->validate([
                    'content' => 'required|string',

                    'sortOrderNo' => 'nullable|integer',
                    'isActive' => 'nullable|boolean',
                    'extraAttributes' => 'nullable|json',
                ]);


                $result = Reply::create([
                    'uniqueId' => uniqid(),

                    'userId' => $currentUser->id,
                    'commentId' => $currentComment->id,

                    'content' => $request->has('content') ? $request->content : null,

                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                    'isActive' => $request->has('isActive') ? $request->isActive : true,
                    'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : null,
                ]);

                if ($result) {
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new ReplyResource($result)
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([]);
                }
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['success' => true, 'message' => 'Comment Not found!']
                ]);
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
    public function show(Request $request, $commentId, $replyId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_reply->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $currentComment = Comment::where('userId', $currentUser->id)->where('uniqueId', $commentId)->first();

            if ($currentComment) {
                $item = Reply::where('uniqueId', $replyId)->where('userId', $currentUser->id)->where('commentId', $currentComment->id)->with('user')->first();

                if ($item) {
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new ReplyResource($item)
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['success' => true, 'message' => 'Reply Not found!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['success' => true, 'message' => 'Comment Not found!']
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
    public function update(Request $request, $commentId, $replyId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_reply->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $currentComment = Comment::where('userId', $currentUser->id)->where('uniqueId', $commentId)->first();

            if ($currentComment) {
                $item = Reply::where('uniqueId', $replyId)->where('userId', $currentUser->id)->where('commentId', $currentComment->id)->first();

                if ($item) {
                    $request->validate([
                        'content' => 'required|string',

                        'sortOrderNo' => 'nullable|integer',
                        'isActive' => 'nullable|boolean',
                        'extraAttributes' => 'nullable|json',
                    ]);

                    $item->update([
                        'content' => $request->has('content') ? $request->content : $item->content,

                        'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->isActive,
                        'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                        'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : $request->extraAttributes,
                    ]);
                    $item = Reply::where('uniqueId', $replyId)->where('userId', $currentUser->id)->where('commentId', $currentComment->id)->with('user')->first();

                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => new ReplyResource($item)
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['success' => true, 'message' => 'Reply Not found!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['success' => true, 'message' => 'Comment Not found!']
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
    public function destroy(Request $request, $commentId, $replyId)
    {

        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_reply->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $currentComment = Comment::where('userId', $currentUser->id)->where('uniqueId', $commentId)->first();

            if ($currentComment) {
                $item = Reply::where('uniqueId', $replyId)->where('userId', $currentUser->id)->where('commentId', $currentComment->id)->first();

                if ($item) {
                    $item->forceDelete();
                    return ZHelpers::sendBackRequestCompletedResponse([
                        'item' => ['success' => true]
                    ]);
                } else {
                    return ZHelpers::sendBackRequestFailedResponse([
                        'item' => ['success' => true, 'message' => 'Reply Not found!']
                    ]);
                }
            } else {
                return ZHelpers::sendBackRequestFailedResponse([
                    'item' => ['success' => true, 'message' => 'Comment Not found!']
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
