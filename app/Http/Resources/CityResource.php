<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Domain\Entities\City;

/**
 * Recurso JSON que representa una ciudad del catálogo.
 *
 * Transforma la entidad de dominio {@see City} al formato expuesto por la API.
 *
 * @mixin City
 */
final class CityResource extends JsonResource
{
    /**
     * Serializa la ciudad a un arreglo asociativo para la respuesta HTTP.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
