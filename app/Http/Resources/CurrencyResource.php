<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
