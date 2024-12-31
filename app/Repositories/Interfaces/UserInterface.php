<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface UserInterface
{
    /**
    * Get all records.
    * @return Collection
    */
    public function getAll(): Collection;

    /**
    * Get all records with pagination.
    * @param Request $request
    */
    public function getAllPaginate(Request $request);

    /**
    * Find a record by its id.
    * @param int $id
    * @return Model|null
    */
    public function find(mixed $id): ?Model;

    /**
    * Create a new record.
    * @param array $attributes
    * @return Model
    */
    public function create(array $attributes): Model;

    /**
    * Update an existing record.
    * @param int $id
    * @param array $attributes
    * @return bool
    */
    public function update(int $id, array $attributes): bool;

    /**
    * Delete an existing record.
    * @param int $id
    * @return bool
    */
    public function delete(int $id): bool;
}
