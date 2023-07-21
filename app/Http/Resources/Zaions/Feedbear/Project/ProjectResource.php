<?php

namespace App\Http\Resources\Zaions\Feedbear\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uniqueId,

            'projectName' => $this->projectName,
            'subDomain' => $this->subDomain,
            'image' => $this->image,
            'squaredIcon' => $this->squaredIcon,
            'accentColor' => $this->accentColor,
            'firstBoardId' => $this->boards->isNotEmpty() ? $this->boards->first()->uniqueId : null,


            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
