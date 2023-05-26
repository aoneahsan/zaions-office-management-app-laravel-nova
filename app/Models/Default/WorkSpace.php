<?php

namespace App\Models\Default;

use App\Models\ZLink\LinkInBios\LinkInBio;
use App\Models\ZLink\ShortLinks\ShortLink;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class WorkSpace extends Model
{
    use HasFactory, SoftDeletes, Actionable;

    protected $guarded = [];

    protected $casts = [
        'workspaceData' => 'array',
        'extraAttributes' => 'array',
    ];

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function shortLinks(): HasMany
    {
        return $this->hasMany(ShortLink::class, 'workspaceId', 'id');
    }

    public function linkInBio(): HasMany
    {
        return $this->hasMany(LinkInBio::class, 'workspaceId', 'id');
    }
}
