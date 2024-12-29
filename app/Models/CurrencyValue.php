<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property int $currency_id
 * @property numeric $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Currency $currency
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CurrencyValue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CurrencyValue newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CurrencyValue query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CurrencyValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CurrencyValue whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CurrencyValue whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CurrencyValue whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CurrencyValue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CurrencyValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CurrencyValue whereValue($value)
 * @mixin \Eloquent
 * @mixin IdeHelperCurrencyValue
 */
class CurrencyValue extends Model
{
    protected $table = 'currency_values';

    protected $fillable = [
        'date',
        'currency_id',
        'value',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'date' => 'datetime:Y-m-d H:i',
            'currency_id' => 'integer',
            'value' => 'decimal:2',
            'created_at' => 'datetime:Y-m-d H:i',
            'updated_at' => 'datetime:Y-m-d H:i',
            'deleted_at' => 'datetime:Y-m-d H:i',
        ];
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
