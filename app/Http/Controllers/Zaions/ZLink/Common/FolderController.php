<?php

namespace App\Http\Controllers\Zaions\ZLink\Common;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\ZLink\Common\FolderResource;
use App\Models\ZLink\Common\Folder;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $workspaceId)
    {
        $userId = $request->user()->id;
        try {
            $itemsCount = Folder::where('userId', $userId)->count();
            $items = Folder::where('userId', $userId)->orderBy('sortOrderNo', 'asc')->get();

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [
                    'items' => FolderResource::collection($items),
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
    public function store(Request $request, $workspaceId)
    {
        $request->validate([
            'title' => 'required|string|max:250',
            'icon' => 'nullable|string|max:250',
            'isStared' => 'nullable|boolean',
            'isHidden' => 'nullable|boolean',
            'isFavorite' => 'nullable|boolean',
            'folderForModel' => 'nullable|string',
            'isPasswordProtected' => 'nullable|boolean',
            'password' => 'nullable|string|max:250',
            'isDefault' => 'nullable|boolean',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);
        $userId = $request->user()->id;
        try {
            $result = Folder::create([
                'uniqueId' => uniqid(),
                'userId' => $userId,
                'title' => $request->has('title') ? $request->title : null,
                'icon' => $request->has('icon') ? $request->icon : null,
                'isStared' => $request->has('isStared') ? $request->isStared : false,
                'isHidden' => $request->has('isHidden') ? $request->isHidden : false,
                'isFavorite' => $request->has('isFavorite') ? $request->isFavorite : false,
                'folderForModel' => $request->has('folderForModel') ? $request->folderForModel : false,
                'isPasswordProtected' => $request->has('isPasswordProtected') ? $request->isPasswordProtected : false,
                'password' => $request->has('password') ? Hash::make($request->password) : null,
                'isDefault' => $request->has('isDefault') ? $request->isDefault : null,
                'isActive' => $request->has('isActive') ? $request->isActive : null,
                'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : null,
            ]);

            if ($result) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new FolderResource($result)
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
    public function show(Request $request, $workspaceId, $itemId)
    {
        $userId = $request->user()->id;
        try {
            $item = Folder::where('uniqueId', $itemId)->where('userId', $userId)->first();

            if ($item) {
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new FolderResource($item)
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
    public function update(Request $request, $workspaceId, $itemId)
    {
        $request->validate([
            'title' => 'required|string|max:250',
            'icon' => 'nullable|string|max:250',
            'isStared' => 'nullable|boolean',
            'isHidden' => 'nullable|boolean',
            'isFavorite' => 'nullable|boolean',
            'folderForModel' => 'nullable|string',
            'isPasswordProtected' => 'nullable|boolean',
            'password' => 'nullable|string|max:250',
            'isDefault' => 'nullable|boolean',
            'isActive' => 'nullable|boolean',
            'extraAttributes' => 'nullable|json',
        ]);

        $userId = $request->user()->id;
        try {
            $item = Folder::where('uniqueId', $itemId)->where('userId', $userId)->first();

            if ($item) {
                $item->update([
                    'title' => $request->has('title') ? $request->title : $item->title,
                    'icon' => $request->has('icon') ? $request->icon : $item->icon,
                    'isStared' => $request->has('isStared') ? $request->isStared : $item->isStared,
                    'isHidden' => $request->has('isHidden') ? $request->isHidden : $item->isHidden,
                    'isFavorite' => $request->has('isFavorite') ? $request->isFavorite : $item->isFavorite,
                    'folderForModel' => $request->has('folderForModel') ? $request->folderForModel : $item->folderForModel,
                    'isPasswordProtected' => $request->has('isPasswordProtected') ? $request->isPasswordProtected : $item->isPasswordProtected,
                    'password' => $request->has('password') ? Hash::make($request->password) : $item->password,
                    'isDefault' => $request->has('isDefault') ? $request->isDefault : $item->isDefault,
                    'isDefault' => $request->has('isDefault') ? $request->isDefault : $item->isDefault,
                    'extraAttributes' => $request->has('extraAttributes') ? $request->extraAttributes : $item->extraAttributes,
                ]);

                $item = Folder::where('uniqueId', $itemId)->where('userId', $userId)->first();
                return ZHelpers::sendBackRequestCompletedResponse([
                    'item' => new FolderResource($item)
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
    public function destroy(Request $request, $workspaceId, $itemId)
    {
        $userId = $request->user()->id;
        try {
            $item = Folder::where('uniqueId', $itemId)->where('userId', $userId)->first();

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

    // public function getFolderShortLinks(Request $request, $workspaceId, $itemId)
    // {
    //     $userId = $request->user()->id;
    //     try {

    //         $itemsCount = ShortLink::where('userId', $userId)->where('folderId', $itemId)->count();
    //         $items = ShortLink::where('userId', $userId)->where('folderId', $itemId)->get();

    //         return response()->json([
    //             'success' => true,
    //             'errors' => [],
    //             'message' => 'Request Completed Successfully!',
    //             'data' => [
    //                 'items' => ShortLinkResource::collection($items),
    //                 'itemsCount' => $itemsCount
    //             ],
    //             'status' => 200
    //         ]);
    //     } catch (\Throwable $th) {
    //         return ZHelpers::sendBackServerErrorResponse($th);
    //     }
    // }

    public function updateSortOrderNo(Request $request, $workspaceId)
    {
        $request->validate([
            'folders' => 'required' // array of all folders ids ()  {id: string}[]
        ]);

        try {
            $folders = $request->input('folders');

            // Loop through the folders and update the sortOrderNo field
            foreach ($folders as $order => $folder) {
                $folder = Folder::where('uniqueId', $folder)->first();
                if ($folder) {
                    $folder->sortOrderNo = $order + 1; // Add 1 to start at 1 instead of 0
                    $folder->save();
                }
            }

            return response()->json([
                'success' => true,
                'errors' => [],
                'message' => 'Request Completed Successfully!',
                'data' => [],
                'status' => 200
            ]);
        } catch (\Throwable $th) {
            return ZHelpers::sendBackServerErrorResponse($th);
        }
    }
}
