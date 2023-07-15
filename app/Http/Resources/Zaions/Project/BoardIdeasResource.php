<?php

namespace App\Http\Resources\Zaions\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardIdeasResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'internalNotes' => $this->internalNotes,
            'image' => $this->image,

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
