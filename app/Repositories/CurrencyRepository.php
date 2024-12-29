<?php

namespace App\Repositories;

use App\Models\Currency;
use App\Repositories\Interfaces\CurrencyInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laymont\PatternRepository\Exceptions\RepositoryException;
use Throwable;

class CurrencyRepository implements CurrencyInterface
{
    public function __construct(protected Currency $model) {}

    public function all($request = null)
    {
        $perPage = $request->get('per_page', 15);

        return $this->model->paginate($perPage)->withQueryString();
    }

    /**
     * @throws RepositoryException
     */
    public function getById(int $id): ?Model
    {
        $model = $this->model::find($id);
        if (! $model) {
            throw new RepositoryException('Registro no encontrado.');
        }

        return $model;
    }

    /**
     * @throws RepositoryException
     */
    public function new(array $attributes): Model
    {
        try {
            return DB::transaction(function () use ($attributes) {
                return $this->model::create($attributes);
            });
        } catch (Throwable $e) {
            Log::error('Error al crear registro: '.$e->getMessage());
            throw new RepositoryException('Error al crear registro', 0, $e);
        }
    }

    /**
     * @throws RepositoryException
     */
    public function update(int $id, array $attributes): bool
    {
        try {
            return DB::transaction(function () use ($id, $attributes) {
                $model = $this->model::findOrFail($id);

                return $model->update($attributes);
            });
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException('Registro no encontrado.', 404, $e);
        } catch (Throwable $e) {
            Log::error('Error al actualizar el registro: '.$e->getMessage());
            throw new RepositoryException('Error al actualizar el registro.', 0, $e);
        }
    }

    /**
     * @throws RepositoryException
     */
    public function delete(int $id): bool
    {
        try {
            $model = $this->model::findOrFail($id);

            return $model->delete();
        } catch (ModelNotFoundException $e) {
            throw new RepositoryException('Registro no encontrado.', 404, $e);
        } catch (Throwable $exception) {
            Log::error('Error al eliminar el registro: '.$exception->getMessage());
            throw new RepositoryException('Error al eliminar el registro.', 0, $exception);
        }
    }
}
