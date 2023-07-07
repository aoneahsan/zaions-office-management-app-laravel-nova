<?php

namespace App\Http\Resources\Zaions\Project;

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
            'featureRequests' => $this->featureRequests,
            'completedRecently' => $this->completedRecently,
            'inProgress' => $this->inProgress,
            'plannedNext' => $this->plannedNext,

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
