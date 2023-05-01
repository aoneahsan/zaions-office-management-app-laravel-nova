<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'extraAttributes' => SchemalessAttributes::class,
    ];

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->extraAttributes->modelScope();
    }
}
