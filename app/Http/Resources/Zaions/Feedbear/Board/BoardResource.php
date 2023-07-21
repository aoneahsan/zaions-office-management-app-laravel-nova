<?php

namespace App\Http\Resources\Zaions\Feedbear\Board;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardResource extends JsonResource
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
            'slug' => $this->slug,
            'pageHeading' => $this->pageHeading,
            'pageDescription' => $this->pageDescription,
            'formCustomization' => $this->formCustomization,
            'defaultStatus' => $this->defaultStatus,
            'votingSetting' => $this->votingSetting,

            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
