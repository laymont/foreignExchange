<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = collect([
            [
                'slug' => Str::uuid(),
                'name' => 'EURO',
                'acronym' => 'EUR',
                'symbol' => '€',
                'is_active' => true,
            ],
            [
                'slug' => Str::uuid(),
                'name' => 'YUAN',
                'acronym' => 'CNY',
                'symbol' => '¥',
                'is_active' => true,
            ],
            [
                'slug' => Str::uuid(),
                'name' => 'LIRA',
                'acronym' => 'TRY',
                'symbol' => '₺',
                'is_active' => true,
            ],
            [
                'slug' => Str::uuid(),
                'name' => 'RUBLO',
                'acronym' => 'RUB',
                'symbol' => '₽',
                'is_active' => true,
            ],
            [
                'slug' => Str::uuid(),
                'name' => 'DOLAR',
                'acronym' => 'USD',
                'symbol' => '$',
                'is_active' => true,
            ],
        ])->each(function ($currency) {
            Currency::create($currency);
        });
    }
}
