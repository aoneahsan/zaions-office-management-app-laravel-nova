<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
// use Spatie\Translatable\HasTranslations;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasSlug, HasTags, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'extraAttributes' => SchemalessAttributes::class,
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->extraAttributes->modelScope();
    }

    public function wantsBreadcrumbs()
    {
        return true;
    }

    public function wantsRTL()
    {
        return false;
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
