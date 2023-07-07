<?php

namespace App\Models\Default;

use App\Models\ZLink\Common\Folder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function folder(): HasMany
    {
        return $this->hasMany(Folder::class, 'workspaceId', 'id');
    }


    // Workspace can belong to many member added by the workspace owner.
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'workspace_members');
    }
}
