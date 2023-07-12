<?php

namespace App\Http\Controllers\Zaions\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Project\BoardIdeaVotesResource;
use App\Models\Default\BoardIdeas;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\ResponseCodesEnum;
use App\Zaions\Enums\ResponseMessagesEnum;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BoardIdeaVotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $boardIdeaId)
    {
        $currentUser = $request->user();
        $currentBoard = BoardIdeas::where('uniqueId', $boardIdeaId)->first();

        Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_boardIdeas->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

        try {
            $itemsCount = BoardIdeas::where('userId', $currentUser->id)->where('boardId', $boardIdeaId)->count();
            $items = BoardIdeas::where('userId', $currentUser->id)->where('boardId', $currentBoard->id)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => BoardIdeaVotesResource::collection($items),
                    'itemsCount' => $itemsCount
                ],
                'status' => 200
            ]);
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
    