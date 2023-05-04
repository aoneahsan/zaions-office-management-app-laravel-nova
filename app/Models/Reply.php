<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class Reply extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => SchemalessAttributes::class,
    ];

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->extraAttributes->modelScope();
    }

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'commentId', 'id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
