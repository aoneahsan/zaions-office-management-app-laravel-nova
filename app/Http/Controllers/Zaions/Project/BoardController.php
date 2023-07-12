<?php

namespace App\Http\Controllers\Zaions\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Project\BoardResource;
use App\Models\Default\Board;
use App\Models\Default\Project;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $projectId)
    {
        $currentUser = $request->user();
        $currentProject = Project::where('uniqueId', $projectId)->first();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_board->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $itemsCount = Board::where('userId', $currentUser->id)->where('projectId', $projectId)->count();
            $items = Board::where('userId', $currentUser->id)->where('projectId', $currentProject->id)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => BoardResource::collection($items),
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
    public function store(Request $request, $projectId)
    {
        $currentUser = $request->user();
        $currentProject = Project::where('uniqueId', $projectId)->first();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::create_board->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        $request->validate([
            'title' => 'required|string',

            'sortOrderNo' => 'nullable|integer',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);


        try {
            $result = Board::create([
                'uniqueId' => uniqid(),

                'userId' => $currentUser->id,
                'projectId' => $currentProject->id,
                'title' => $request->has('title') ? $request->title : null,

                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive : null,
                'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new BoardResource($result)
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
    public function show(Request $request, $projectId, $itemId)
    {
        $currentUser = $request->user();

        $currentProject = Project::where('uniqueId', $projectId)->first();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::view_board->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $item = Board::where('uniqueId', $itemId)->where('projectId', $currentProject->id)->where('userId', $currentUser->id)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new BoardResource($item)
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
    public function update(Request $request, $projectId, $itemId)
    {
        try {
            $currentUser = $request->user();
            $currentProject = Project::where('uniqueId', $projectId)->first();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::update_board->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $request->validate([
                'title' => 'required|string',

                'sortOrderNo' => 'nullable|integer',
                'isActive' => 'nullable|boolean',
                'extraAttributes' => 'nullable|json',
            ]);

            $item = Board::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('projectId', $currentProject->id)->first();

            if ($item) {
                $item->update([
                    'title' => $request->has('title') ? $request->title : $item->title,


                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $item->isActive,
                    'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? (is_string($request->extraAttributes) ? json_decode($request->extraAttributes) : $request->extraAttributes) : $request->extraAttributes,
                ]);

                $item = Board::where('uniqueId', $itemId)->where('userId', $currentUser->id)->first();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new BoardResource($item)
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
    public function destroy(Request $request, $projectId, $itemId)
    {
        $currentUser = $request->user();
        $currentProject = Project::where('uniqueId', $projectId)->first();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::delete_board->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);


        try {
            $item = Board::where('uniqueId', $itemId)->where('userId', $currentUser->id)->where('projectId', $currentProject->id)->first();

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
