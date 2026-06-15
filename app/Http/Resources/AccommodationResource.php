<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Domain\Entities\Accommodation;

/**
 * Recurso JSON que representa una acomodación del catálogo.
 *
 * Transforma la entidad de dominio {@see Accommodation} al formato expuesto por la API.
 *
 * @mixin Accommodation
 */
final class AccommodationResource extends JsonResource
{
    /**
     * Serializa la acomodación a un arreglo asociativo para la respuesta HTTP.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
        ];
    }
}
