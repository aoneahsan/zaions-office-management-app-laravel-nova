<?php

namespace App\Models\FPI;

use App\Models\User;
use App\Zaions\Enums\FPI\Projects\ProjectTransactionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class ProjectTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => 'array'
    ];

    // Relationship methods
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function project(): HasOne
    {
        return $this->hasOne(Project::class, 'id', 'projectId');
    }

    public function seller(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'sellerId');
    }

    public function buyer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'buyerId');
    }
}
