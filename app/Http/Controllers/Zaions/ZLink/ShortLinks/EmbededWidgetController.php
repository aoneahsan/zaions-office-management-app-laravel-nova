<?php

namespace App\Http\Controllers\Zaions\ZLink\ShortLinks;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\ShortLinks\EmbededWidgetResource;
use App\Models\Default\WorkSpace;
use App\Models\ZLink\ShortLinks\EmbededWidget;
use App\Models\ZLink\ShortLinks\ShortLink;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;

class EmbededWidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $workspaceId, $shortLinkId)
    {
        $userId = $request->user()->id;
        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $userId)->first();

        // getting Short-link in workspace
        $ShortLink = ShortLink::where('uniqueId', $shortLinkId)->where('userId', $userId)->where('workspaceId', $workspace->id)->first();

        if (!$ShortLink) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No Short-link found!']
            ]);
        }

        try {
            $itemsCount = EmbededWidget::where('userId', $userId)->count();
            $items = EmbededWidget::where('userId', $userId)->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => EmbededWidgetResource::collection($items),
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
    public function store(Request $request, $workspaceId, $shortLinkId)
    {
        $userId = $request->user()->id;
        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $userId)->first();

        // getting Short-link in workspace
        $ShortLink = ShortLink::where('uniqueId', $shortLinkId)->where('userId', $userId)->where('workspaceId', $workspace->id)->first();

        if (!$ShortLink) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No Short-link found!']
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:250',
            'canCodeJs' => 'nullable|boolean',
            'canCodeHtml' => 'nullable|boolean',
            'jsCode' => 'nullable|string|max:1000',
            'HTMLCode' => 'nullable|string|max:1000',
            'displayAt' => 'nullable|string',
            'delay' => 'nullable|integer',
            'position' => 'nullable|string',
            'animation' => 'nullable|boolean',
            'closingOption' => 'nullable|boolean',

            'isActive' => 'nullable|boolean',
            'sortOrderNo' => 'nullable|integer',
            'extraAttributes' => 'nullable|json'
        ]);
        $userId = $request->user()->id;
        try {
            $result = EmbededWidget::create([
                'uniqueId' => uniqid(),
                'userId' => $userId,
                'shortLinkId' => $ShortLink->id,
                'name' => $request->has('name') ? $request->name : null,
                'canCodeJs' => $request->has('canCodeJs') ? $request->canCodeJs : false,
                'canCodeHtml' => $request->has('canCodeHtml') ? $request->canCodeHtml : false,
                'jsCode' => $request->has('jsCode') ? $request->jsCode : null,
                'HTMLCode' => $request->has('HTMLCode') ? $request->HTMLCode : null,
                'displayAt' => $request->has('displayAt') ? $request->displayAt : null,
                'delay' => $request->has('delay') ? $request->delay : null,
                'position' => $request->has('position') ? $request->position : null,
                'animation' => $request->has('animation') ? $request->animation : false,
                'closingOption' => $request->has('closingOption') ? $request->closingOption : false,

                'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : null,
                'isActive' => $request->has('isActive') ? $request->isActive : null,
                'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new EmbededWidgetResource($result)
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
    public function show(Request $request, $workspaceId, $shortLinkId, $itemId)
    {
        $userId = $request->user()->id;
        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $userId)->first();

        // getting Short-link in workspace
        $ShortLink = ShortLink::where('uniqueId', $shortLinkId)->where('userId', $userId)->where('workspaceId', $workspace->id)->first();

        if (!$ShortLink) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No Short-link found!']
            ]);
        }

        try {
            $item = EmbededWidget::where('uniqueId', $itemId)->where('userId', $userId)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new EmbededWidgetResource($item)
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
    public function update(Request $request, $workspaceId, $shortLinkId, $itemId)
    {
        $userId = $request->user()->id;
        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $userId)->first();

        // getting Short-link in workspace
        $ShortLink = ShortLink::where('uniqueId', $shortLinkId)->where('userId', $userId)->where('workspaceId', $workspace->id)->first();

        if (!$ShortLink) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No Short-link found!']
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:250',
            'canCodeJs' => 'nullable|boolean',
            'canCodeHtml' => 'nullable|boolean',
            'jsCode' => 'nullable|string|max:1000',
            'HTMLCode' => 'nullable|string|max:1000',
            'displayAt' => 'nullable|string',
            'delay' => 'nullable|integer',
            'position' => 'nullable|string',
            'animation' => 'nullable|boolean',
            'closingOption' => 'nullable|boolean',

            'isActive' => 'nullable|boolean',
            'sortOrderNo' => 'nullable|integer',
            'extraAttributes' => 'nullable|json'
        ]);

        $userId = $request->user()->id;
        try {
            $item = EmbededWidget::where('uniqueId', $itemId)->where('userId', $userId)->where('shortLinkId', $ShortLink->id)->first();

            if ($item) {
                $item->update([
                    'name' => $request->has('name') ? $request->name : $item->name,
                    'canCodeJs' => $request->has('canCodeJs') ? $request->canCodeJs : $item->canCodeJs,
                    'canCodeHtml' => $request->has('canCodeHtml') ? $request->canCodeHtml : $item->canCodeHtml,
                    'jsCode' => $request->has('jsCode') ? $request->jsCode : $item->jsCode,
                    'HTMLCode' => $request->has('HTMLCode') ? $request->HTMLCode : $item->HTMLCode,
                    'displayAt' => $request->has('displayAt') ? $request->displayAt : $item->displayAt,
                    'delay' => $request->has('delay') ? $request->delay : $item->delay,
                    'position' => $request->has('position') ? $request->position : $item->position,
                    'animation' => $request->has('animation') ? $request->animation : $item->animation,
                    'closingOption' => $request->has('closingOption') ? $request->closingOption : $item->closingOption,

                    'sortOrderNo' => $request->has('sortOrderNo') ? $request->sortOrderNo : $request->sortOrderNo,
                    'isActive' => $request->has('isActive') ? $request->isActive : $request->isActive,
                    'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : $request->extraAttributes,
                ]);

                $item = EmbededWidget::where('uniqueId', $itemId)->where('userId', $userId)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new EmbededWidgetResource($item)
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
    public function destroy(Request $request, $workspaceId, $shortLinkId, $itemId)
    {
        $userId = $request->user()->id;
        // getting workspace
        $workspace = WorkSpace::where('uniqueId', $workspaceId)->where('userId', $userId)->first();

        // getting Short-link in workspace
        $ShortLink = ShortLink::where('uniqueId', $shortLinkId)->where('userId', $userId)->where('workspaceId', $workspace->id)->first();

        if (!$ShortLink) {
            return ZHelpers::sendBackInvalidParamsResponse([
                "item" => ['No Short-link found!']
            ]);
        }

        try {
            $item = EmbededWidget::where('uniqueId', $itemId)->where('userId', $userId)->first();

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
