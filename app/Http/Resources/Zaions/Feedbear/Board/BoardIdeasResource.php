<?php

namespace App\Http\Resources\Zaions\Feedbear\Board;

use App\Http\Resources\Zaions\Default\CommentResource;
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
            'user' => $this->user ? [
                'username' => $this->user->username,
                'profilePitcher' => $this->user->profilePitcher,
                'email' => $this->user->email,
            ] : null,
            'comments' => $this->comments ? CommentResource::collection($this->comments) : null,

            'votesCount' => $this->votes_count,
            'commentsCount' => $this->comments_count,
            'sortOrderNo' => $this->sortOrderNo,
            'isActive' => $this->isActive,
            'extraAttributes' => $this->extraAttributes,
            'createdAt' => $this->created_at->diffForHumans(),
            'updatedAt' => $this->updated_at->diffForHumans(),
        ];
    }
}
