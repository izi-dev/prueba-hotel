<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Domain\Entities\RoomType;

/**
 * Recurso JSON que representa un tipo de habitación del catálogo.
 *
 * Transforma la entidad de dominio {@see RoomType} al formato expuesto por la API.
 *
 * @mixin RoomType
 */
final class RoomTypeResource extends JsonResource
{
    /**
     * Serializa el tipo de habitación a un arreglo asociativo para la respuesta HTTP.
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
