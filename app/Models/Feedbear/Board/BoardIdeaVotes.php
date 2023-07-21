<?php

namespace App\Models\Feedbear\Board;

use App\Models\Default\User;
use App\Models\Feedbear\Project\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class BoardIdeaVotes extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',
    ];

    // Relationship methods
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'userId', 'id');
    }

    public function project(): HasOne
    {
        return $this->hasOne(Project::class, 'projectId', 'id');
    }

    public function board(): HasOne
    {
        return $this->hasOne(Board::class, 'boardId', 'id');
    }

    public function boardIdea(): BelongsTo
    {
        return $this->belongsTo(BoardIdeas::class, 'boardIdeaId', 'id');
    }
}
