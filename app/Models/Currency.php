<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @mixin Eloquent
 * @mixin IdeHelperCurrency
 */
class Currency extends Model
{
    protected $table = 'currencies';

    protected $fillable = [
        'slug',
        'name',
        'symbol',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'slug' => 'string',
            'name' => 'string',
            'symbol' => 'string',
            'is_active' => 'boolean',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }

    public function values(): HasMany
    {
        return $this->hasMany(CurrencyValue::class);
    }

    public function lastValue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->values()->latest()->first()->value ?? null,
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: static fn (string $value) => ['name' => $value, 'slug' => Str::uuid()]
        );
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
