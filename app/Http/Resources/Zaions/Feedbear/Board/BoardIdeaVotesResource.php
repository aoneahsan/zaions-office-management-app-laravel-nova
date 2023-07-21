<?php

namespace App\Http\Resources\Zaions\Feedbear\Board;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoardIdeaVotesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
