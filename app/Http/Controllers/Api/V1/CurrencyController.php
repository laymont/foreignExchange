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
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class CurrencyController extends Controller
{
    public function __construct(protected CurrencyInterface $currency) {}

    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *      path="/api/v1/currencies",
     *      operationId="getCurrencies",
     *      tags={"Currencies"},
     *      summary="Get list of currencies",
     *       security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/CurrencyResource")
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       ),
     *     )
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
     * @OA\Post(
     *      path="/api/v1/currencies",
     *      operationId="storeCurrency",
     *      tags={"Currencies"},
     *      summary="Store new currency",
     *      security={{"bearerAuth":{}}},
     *       @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreCurrencyRequest")
     *       ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CurrencyResource")
     *       ),
     *       @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       ),
     *        @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *       ),
     *     )
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
     *
     * @OA\Get(
     *      path="/api/v1/currencies/{id}",
     *      operationId="showCurrency",
     *      tags={"Currencies"},
     *      summary="Get currency information",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Currency id or slug",
     *          required=true,
     *           @OA\Schema(
     *              type="string"
     *           )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/CurrencyResource")
     *       ),
     *       @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       ),
     *       @OA\Response(
     *          response=404,
     *          description="Resource Not Found",
     *       ),
     *     )
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
     *
     * @OA\Put(
     *      path="/api/v1/currencies/{id}",
     *      operationId="updateCurrency",
     *      tags={"Currencies"},
     *      summary="Update existing currency",
     *       security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Currency id",
     *          required=true,
     *           @OA\Schema(
     *              type="integer"
     *           )
     *      ),
     *       @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateCurrencyRequest")
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *            @OA\JsonContent(ref="#/components/schemas/CurrencyResource")
     *       ),
     *       @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       ),
     *        @OA\Response(
     *          response=404,
     *          description="Resource Not Found",
     *       ),
     *       @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *       ),
     *     )
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
     *
     *  @OA\Delete(
     *      path="/api/v1/currencies/{id}",
     *      operationId="deleteCurrency",
     *      tags={"Currencies"},
     *      summary="Delete existing currency",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Currency id",
     *          required=true,
     *           @OA\Schema(
     *              type="integer"
     *           )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json"
     *           )
     *       ),
     *        @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       ),
     *       @OA\Response(
     *          response=404,
     *          description="Resource Not Found",
     *       ),
     *       @OA\Response(
     *          response=500,
     *          description="Server Internal Error",
     *       ),
     *     )
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
