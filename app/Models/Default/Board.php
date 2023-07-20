<?php

namespace App\Models\Default;

use App\Models\Feedbear\status\BoardStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class Board extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',
        'formCustomization' => 'array',
        'defaultStatus' => 'array',
        'votingSetting' => 'array',
    ];

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'projectId', 'id');
    }

    public function boardIdeas(): HasMany
    {
        return $this->hasMany(BoardIdeas::class);
    }

    public function boardStatus(): HasMany
    {
        return $this->hasMany(BoardStatus::class);
    }
}
