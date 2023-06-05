<?php

namespace App\Models\FPI;

use App\Models\Default\Attachment;
use App\Models\User;
use App\Zaions\Helpers\ZHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Actions\Actionable;

class Project extends Model
{
    // use HasFactory, SoftDeletes, Actionable;
    use HasFactory, SoftDeletes;

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

    public function projectTransactions(): HasMany
    {
        $user = Auth::user();
        $query = $this->hasMany(ProjectTransaction::class, 'projectId', 'id');
        if (ZHelpers::isAdminLevelUser($user)) {
            return $query;
        } else {
            return $query->where('userId', $user->id);
        }
    }
}
