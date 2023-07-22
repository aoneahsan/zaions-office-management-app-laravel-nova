<?php

namespace App\Http\Controllers\Zaions\Feedbear\Board;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\Feedbear\Board\BoardIdeaVotesResource;
use App\Models\Feedbear\Board\Board;
use App\Models\Feedbear\Board\BoardIdeas;
use App\Models\Feedbear\Board\BoardIdeaVotes;
use App\Models\Feedbear\Project\Project;
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
    public function index(Request $request, $projectId, $boardId, $boardIdeaId)
    {
        try {
            $currentUser = $request->user();

            Gate::allowIf($currentUser->hasPermissionTo(PermissionsEnum::viewAny_boardIdeas->name), ResponseMessagesEnum::Unauthorized->name, ResponseCodesEnum::Unauthorized->name);

            $currentProject = Project::where('uniqueId', $projectId)->where('userId', $currentUser->id)->first();

            $currentBoard = Board::where('uniqueId', $boardId)->where('userId', $currentUser->id)->first();

            $currentBoardIdea = BoardIdeas::where('uniqueId', $boardIdeaId)->where('userId', $currentUser->id)->first();

            $isVoteExist = BoardIdeaVotes::where('userId', $currentUser->id)->where('projectId', $currentProject->id)->where('boardId', $currentBoard->id)->where('boardIdeaId', $currentBoardIdea->id)->first();


            if ($isVoteExist) {
                $isVoteExist->forceDelete();

                $totalVotes = BoardIdeaVotes::where('projectId', $currentProject->id)->where('boardId', $currentBoard->id)->where('boardIdeaId', $currentBoardIdea->id)->count();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => [
                        'success' => true,
                        'voteWasAdded' => false, // means the vote is remove from idea. used to change color in frontend etc.
                        'totalVotes' => $totalVotes
                    ]
                ]);
            } else {
                BoardIdeaVotes::create([
                    'uniqueId' => uniqid(),
                    'userId' => $currentUser->id,
                    'projectId' => $currentProject->id,
                    'boardId' => $currentBoard->id,
                    'boardIdeaId' => $currentBoardIdea->id,
                ]);

                $totalVotes = BoardIdeaVotes::where('projectId', $currentProject->id)->where('boardId', $currentBoard->id)->where('boardIdeaId', $currentBoardIdea->id)->count();

                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => [
                        'success' => true,
                        'voteWasAdded' => true, // means the vote is added for idea. used to change color in frontend etc.
                        'totalVotes' => $totalVotes
                    ]
                ]);
            }
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
