<?php

namespace App\Http\Controllers\Zaions\ZLink\LinkInBios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\LinkInBios\LibPredefinedDataResource;
use App\Models\Default\WorkSpace;
use App\Models\ZLink\LinkInBios\LibPredefinedData;
use App\Models\ZLink\LinkInBios\LinkInBio;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;

class LibPredefinedDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $workspaceId, $linkInBioId)
    {
        try {
            $userId = $request->user()->id;
            // getting workspace
            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $userId)->first();

            // getting link-in-bio in workspace
            $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $userId)->where('workspaceId', $workspace->id)->first();

            if (!$linkInBio) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No link-in-bio found!']
                ]);
            }

            $itemsCount = LibPredefinedData::where('linkInBioId', $linkInBio->id)->where('userId', $userId)->count();
            $items = LibPredefinedData::where('linkInBioId', $linkInBio->id)->where('userId', $userId)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => LibPredefinedDataResource::collection($items),
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
    public function store(Request $request, $workspaceId, $linkInBioId)
    {

        $userId = $request->user()->id;

        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $userId)->first();

        // getting link-in-bio in workspace
        $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $userId)->where('workspaceId', $workspace->id)->first();

        if (!$linkInBio) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No link-in-bio found!']
            ]);
        }

        $request->validate([
            'type' => 'required|string',
            'icon' => 'required|string',
            'title' => 'required|string',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        try {
            $result = LibPredefinedData::create([
                'uniqueId' => uniqid(),
                'user_id' => $userId,
                'type' => $request->has('type') ? $request->type : null,
                'icon' => $request->has('icon') ? $request->icon : null,
                'title' => $request->has('title') ? $request->title : null,
                'isActive' => $request->has('isActive') ? $request->isActive : null,
                'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([]);
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
    public function show(Request $request, $workspaceId, $linkInBioId, $itemId)
    {
        try {
            $userId = $request->user()->id;

            // getting workspace
            $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $userId)->first();

            // getting link-in-bio in workspace
            $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $userId)->where('workspaceId', $workspace->id)->first();

            if (!$linkInBio) {
                return ZHelpers::sendBackInvalidParamsResponse([
                    "item" => ['No link-in-bio found!']
                ]);
            }

            $item = LibPredefinedData::where('uniqueId', $itemId)->where('linkInBioId', $linkInBio->id)->where('userId', $userId)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LibPredefinedDataResource($item)
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
    public function update(Request $request, $workspaceId, $linkInBioId, $itemId)
    {

        $userId = $request->user()->id;

        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $userId)->first();

        // getting link-in-bio in workspace
        $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $userId)->where('workspaceId', $workspace->id)->first();

        if (!$linkInBio) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No link-in-bio found!']
            ]);
        }

        $request->validate([
            'type' => 'required|string',
            'icon' => 'required|string',
            'title' => 'required|string',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        try {
            $item = LibPredefinedData::where('uniqueId', $itemId)->where('linkInBioId', $linkInBio->id)->where('userId', $userId)->first();

            if ($item) {
                $item->update([
                    'type' => $request->has('type') ? $request->type : $item->type,
                    'icon' => $request->has('icon') ? $request->icon : $item->icon,
                    'title' => $request->has('title') ? $request->title : $item->title,
                    'isActive' => $request->has('isActive') ? $request->isActive : $item->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : $item->extraAttributes,
                ]);

                $item = LibPredefinedData::where('unique_id', $itemId)->where('user_id', $userId)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new LibPredefinedDataResource($item)
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
    public function destroy(Request $request, $workspaceId, $linkInBioId, $itemId)
    {
        $userId = $request->user()->id;

        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $userId)->first();

        // getting link-in-bio in workspace
        $linkInBio = LinkInBio::where('uniqueId', $linkInBioId)->where('userId', $userId)->where('workspaceId', $workspace->id)->first();

        if (!$linkInBio) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No link-in-bio found!']
            ]);
        }

        try {
            $item = LibPredefinedData::where('uniqueId', $itemId)->where('linkInBioId', $linkInBio->id)->where('userId', $userId)->first();

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
