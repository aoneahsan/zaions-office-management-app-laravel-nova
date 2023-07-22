<?php

namespace App\Http\Resources\Zaions\Default;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'commentable_type' => $this->commentable_type,
            'commentableId' => $this->commentable_id,
            'content' => $this->content,
            'user' => $this->user ? [
                'username' => $this->user->username,
                'profilePitcher' => $this->user->profilePitcher,
                'email' => $this->user->email,
            ] : null,
            'replies' => $this->replies ? ReplyResource::collection($this->replies) : null,

            'isActive' => $this->isActive,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at
        ];
    }
}
