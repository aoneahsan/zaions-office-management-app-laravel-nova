<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Zaions\Enums\RolesEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Actions\Actionable;
use Laravel\Nova\Auth\Impersonatable;
use Laravel\Sanctum\HasApiTokens;
use Mostafaznv\NovaMapField\Traits\HasSpatialColumns;
use MultiPolygon;
use Outl1ne\NovaNotesField\Traits\HasNotes;
use Point;
use Polygon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\EloquentSortable\SortableTrait;
use Spatie\Permission\Traits\HasRoles;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use Visanduma\NovaTwoFactor\ProtectWith2FA;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class ZTestingDemo extends Model
{
    // use HasApiTokens, HasFactory, Notifiable, HasRoles, HasSlug, HasTags, SoftDeletes, Impersonatable, Actionable, LogsActivity, SortableTrait, ProtectWith2FA, HasNotes, HasFlexible, HasSpatialColumns;
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasSlug, HasTags, SoftDeletes, Impersonatable, Actionable, SortableTrait, ProtectWith2FA, HasNotes;

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
        'email_verified_at' => 'datetime',
        'extraAttributes' => SchemalessAttributes::class,
        'openingHoursData' => 'array',
        'jsonFieldContent' => 'array',
        'unlayerEmailMakerField' => 'array',
        'flexableContent' => FlexibleCast::class,
        'map-field-location' => Point::class,
        'map-field-area'     => Polygon::class,
        'map-field-areas'    => MultiPolygon::class
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
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
    // https://novapackages.com/packages/bolechen/nova-activitylog package setting - ends
}
