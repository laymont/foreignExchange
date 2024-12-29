<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface CurrencyInterface
{
    public function all(?Request $request = null);

    public function getById(int $id): ?Model;

    public function new(array $attributes): Model;

    public function update(int $id, array $attributes): bool;

    public function delete(int $id): bool;
}
