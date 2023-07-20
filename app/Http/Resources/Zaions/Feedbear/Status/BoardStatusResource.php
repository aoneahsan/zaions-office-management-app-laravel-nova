<?php

namespace App\Http\Resources\Zaions\Feedbear\Status;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardStatusResource extends JsonResource
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
            'color' => $this->color,
            'isDefault' => $this->isDefault,
            'isEditable' => $this->isEditable,
            'isDeletable' => $this->isDeletable,

            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
