<?php

namespace App\Http\Resources\Zaions\Default;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
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
            'commentId' => $this->commentId,
            'content' => $this->content,
            'user' => $this->user ? [
                'username' => $this->user->username,
                'profilePitcher' => $this->user->profilePitcher,
                'email' => $this->user->email,
            ] : null,

            'isActive' => $this->isActive,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans(),
        ];
    }
}
