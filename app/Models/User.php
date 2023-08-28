<?php

namespace App\Models;

use App\Zaions\Enums\RolesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    // protected $fillable = ['name', 'text'];

    protected $guarded = [];

    // bolechen/nova-activitylog
    protected static $logAttributes = ['name', 'email'];

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

    /**
     * Determine if the user can impersonate another user.
     *
     * @return bool
     */
    public function canImpersonate()
    {
        // return Gate::forUser($this)->check('viewNova');
        return $this->hasRole(RolesEnum::superAdmin->name);
    }

    /**
     * Determine if the user can be impersonated.
     *
     * @return bool
     */
    public function canBeImpersonated()
    {
        // return true;
        return !$this->hasRole(RolesEnum::superAdmin->name);
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
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'userId', 'id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    // https://novapackages.com/packages/ebess/advanced-nova-media-library package setting - starts
    // public function registerMediaConversions(Media $media = null): void
    // {
    //     $this->addMediaConversion('thumb')
    //         ->width(130)
    //         ->height(130);
    // }

    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('main')->singleFile();
    //     $this->addMediaCollection('my_multi_collection');
    // }
    // https://novapackages.com/packages/ebess/advanced-nova-media-library package setting - ends

    // https://novapackages.com/packages/bolechen/nova-activitylog package setting - starts
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults();
    // }
    // https://novapackages.com/packages/bolechen/nova-activitylog package setting - ends
}
