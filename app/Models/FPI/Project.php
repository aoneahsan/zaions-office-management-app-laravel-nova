<?php

namespace App\Models\FPI;

use App\Models\Default\Attachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class Project extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array',
        'bankDetails' => 'array',
        'coordinates' => 'array'
    ];

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
