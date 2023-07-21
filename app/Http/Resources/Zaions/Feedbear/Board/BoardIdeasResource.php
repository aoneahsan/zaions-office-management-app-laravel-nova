<?php

namespace App\Http\Resources\Zaions\Feedbear\Board;

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
            'statusUniqueId' => $this->statusUniqueId,
            'internalNotes' => $this->internalNotes,
            'image' => $this->image,
            'tags' => $this->tags,
            'isCompleted' => $this->isCompleted,

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'votesCount' => $this->votes_count
        ];
    }
}
