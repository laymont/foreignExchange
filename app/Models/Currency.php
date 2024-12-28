<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $table = 'currencies';
    protected $fillable = [
        'name',
        'symbol',
        'is_active',
    ];
    protected function casts(): array
    {
        return [
            'id' => 'integer',
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
}
