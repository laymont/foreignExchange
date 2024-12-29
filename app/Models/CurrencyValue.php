<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
