<?php

namespace App\Models;

use App\Models\Default\Attachment;
use App\Models\Default\Comment;
use App\Zaions\Enums\PermissionsEnum;
use App\Zaions\Enums\RolesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Actions\Actionable;
use Laravel\Nova\Auth\Impersonatable;
use Laravel\Sanctum\HasApiTokens;
use Outl1ne\NovaNotesField\Traits\HasNotes;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Tags\HasTags;
use Visanduma\NovaTwoFactor\ProtectWith2FA;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasTags, SoftDeletes, Impersonatable, Actionable, SortableTrait, ProtectWith2FA, HasNotes;

    protected $guarded = [];

    // https://novapackages.com/packages/outl1ne/nova-sortable
    public $sortable = [
        'order_column_name' => 'sortOrderNo',
        'sort_when_creating' => true,
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'extraAttributes' => 'array',
        'email_verified_at' => 'datetime',
    ];

    public function wantsBreadcrumbs()
    {
        return true;
    }

    public function wantsRTL()
    {
        return false;
    }

    public function canImpersonate()
    {
        return $this->hasPermissionTo(PermissionsEnum::can_impersonate->name);
    }

    public function canBeImpersonated()
    {
        return $this->hasPermissionTo(PermissionsEnum::canBe_impersonate->name) && !$this->hasRole(RolesEnum::superAdmin->name);
    }

    // User Modal Attributes getter functions
    public function getUserTimezoneAttribute()
    {
        // $this->timezone will get set by user or admin on profile setting page, otherwise we will use "Asia/Karachi"
        if ($this->timezone) {
            return $this->timezone;
        } else {
            return "Asia/Karachi";
        }
    }

    // Relationship data methods
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
