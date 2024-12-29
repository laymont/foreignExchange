<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCurrencyRequest;
use App\Http\Requests\UpdateCurrencyRequest;
use App\Http\Resources\CurrencyResource;
use App\Repositories\Interfaces\CurrencyInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CurrencyController extends Controller
{
    public function __construct(protected CurrencyInterface $currency) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $collection = $this->currency->all($request);
        $resource = CurrencyResource::collection($collection);

        return response()->json($resource, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Exception
     */
    public function store(StoreCurrencyRequest $request): JsonResponse
    {
        try {
            $currency = $this->currency->new($request->validated());
            $resource = new CurrencyResource($currency);

            return response()->json($resource, Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            Log::error('Error registrando nueva moneda', ['message' => $e->getMessage()]);

            return response()->json([
                'message' => 'Error registrando nueva moneda: '.$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $currency = $this->currency->getById($id);
            $resource = new CurrencyResource($currency);

            return response()->json($resource, Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Error al obtener moneda: '.$e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCurrencyRequest $request, string $id): JsonResponse
    {
        try {
            $this->currency->update((int) $id, $request->validated());
            $currency = $this->currency->getById($id);
            $resource = new CurrencyResource($currency);

            return response()->json($resource, Response::HTTP_OK);

        } catch (\Throwable $e) {
            Log::error('Error al actualizar moneda', ['message' => $e->getMessage()]);

            return response()->json([
                'message' => 'Error al actualizar moneda: '.$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->currency->delete((int) $id);

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $e) {
            Log::error('Error al eliminar moneda', ['message' => $e->getMessage()]);

            return response()->json([
                'message' => 'Error al eliminar moneda: '.$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
