<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $acronym
 * @property mixed $symbol
 * @property mixed $created_at
 * @property mixed $slug
 * @property mixed $last_value
 * @property mixed $is_active
 */
/**
 * @OA\Schema(
 *      title="CurrencyResource",
 *      description="Currency Resource response",
 *     type="object",
 *      @OA\Property(
 *          property="id",
 *           type="integer",
 *            example=1,
 *           description="Id of currency"
 *      ),
 *      @OA\Property(
 *          property="name",
 *           type="string",
 *            example="Dolar",
 *            description="Name of currency"
 *      ),
 *      @OA\Property(
 *          property="acronym",
 *           type="string",
 *            example="USD",
 *           description="Acronym of currency"
 *      ),
 *      @OA\Property(
 *          property="symbol",
 *           type="string",
 *            example="$",
 *           description="Symbol of currency"
 *      ),
 *      @OA\Property(
 *          property="is_active",
 *          type="boolean",
 *           example=true,
 *            description="Status of currency"
 *      ),
 *    @OA\Property(
 *          property="last_value",
 *           type="number",
 *            example=24.34,
 *            description="Last value of currency"
 *      ),
 *      @OA\Property(
 *          property="slug",
 *          type="string",
 *           example="dolar",
 *           description="Slug of currency"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string",
 *          example="2024-05-17 09:31:22",
 *          description="Date of creation"
 *      ),
 *        @OA\Property(
 *          property="updated_at",
 *          type="string",
 *          example="2024-05-17 09:31:22",
 *          description="Date of last update"
 *      )
 * )
 */
class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'acronym' => $this->acronym,
            'symbol' => $this->symbol,
            'is_active' => $this->is_active,
            'last_value' => $this->last_value,
            'slug' => $this->slug,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'updated_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
