<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Laymont\PatternRepository\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Laymont\PatternRepository\Concerns\HandlePerPageTrait;

class UserRepository implements UserInterface
{
    use HandlePerPageTrait;

    public function __construct(protected User $model) {}

    /**
    * Get all records.
    * @return Collection
    */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
    * Get all records with pagination.
    * @param Request $request
    * @return mixed
    */
    public function getAllPaginate(Request $request): mixed
    {
        return $this->model::paginate($this->getPerPage($request))->withQueryString();
    }

    /**
    * Find a record by its id.
    * @param int $id
    * @return Model|null
    */
    public function find(mixed $id): ?Model
    {
        return $this->model::query()->findOrFail($id);
    }

    /**
    * Create a new record.
    * @param array $attributes
    * @return Model
    * @throws RepositoryException
    */
    public function create(array $attributes): Model
    {
         try {
            return DB::transaction(function () use ($attributes) {
                return $this->model::create($attributes);
            });
        } catch (Throwable $e) {
            Log::error('Error al crear registro User ' , ['message' => $e->getMessage()]);
            throw new RepositoryException('Error al crear registro User', 0, $e);
        }
    }

    /**
    * Update an existing record.
    * @param int $id
    * @param array $attributes
    * @return bool
    * @throws RepositoryException
    */
    public function update(int $id, array $attributes): bool
    {
         try {
            return DB::transaction(function () use ($id, $attributes) {
                return $this->model::query()->where('id', $id)->update($attributes);
            });
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException('Registro no encontrado.', 404, $e);
        }catch (Throwable $e) {
            Log::error( 'Error al actualizar el registro: ' . $e->getMessage());
            throw new RepositoryException('Error al actualizar el registro.', 0, $e);
        }
    }

    /**
    * Delete an existing record.
    * @param int $id
    * @return bool
    * @throws RepositoryException
    */
    public function delete(int $id): bool
    {
          try {
            return (bool) $this->model::destroy($id);
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException('Registro no encontrado.', 404, $e);
        } catch (Throwable $exception) {
            Log::error('Error al eliminar el registro: '.$exception->getMessage());
            throw new RepositoryException('Error al eliminar el registro.', 0, $exception);
        }
    }
}
