<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = collect([
            [
                'name' => 'euro',
                'acronym' => 'EUR',
                'symbol' => '€',
                'is_active' => true,
            ],
            [
                'name' => 'yuan',
                'acronym' => 'CNY',
                'symbol' => '¥',
                'is_active' => true,
            ],
            [
                'name' => 'lira',
                'acronym' => 'TRY',
                'symbol' => '₺',
                'is_active' => true,
            ],
            [
                'name' => 'rublo',
                'acronym' => 'RUB',
                'symbol' => '₽',
                'is_active' => true,
            ],
            [
                'name' => 'dolar',
                'acronym' => 'USD',
                'symbol' => '$',
                'is_active' => true,
            ]
        ])->each(function ($currency) {
            Currency::create($currency);
        });
    }
}
