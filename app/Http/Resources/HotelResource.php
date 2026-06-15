<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Domain\Entities\Hotel;
use Src\Domain\Entities\HotelRoomConfiguration;

/**
 * Recurso JSON que representa un hotel.
 *
 * Transforma la entidad de dominio {@see Hotel} al formato expuesto por la API,
 * incluyendo el resumen de habitaciones configuradas y disponibles.
 *
 * @mixin Hotel
 */
final class HotelResource extends JsonResource
{
    /**
     * Serializa el hotel a un arreglo asociativo para la respuesta HTTP.
     *
     * Calcula las habitaciones configuradas y las disponibles a partir
     * de las configuraciones de habitación asociadas.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $configuredRooms = array_sum(array_map(
            fn (HotelRoomConfiguration $config): int => $config->quantity,
            $this->roomConfigurations,
        ));

        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'city_id' => $this->cityId,
            'city_name' => $this->cityName,
            'nit' => $this->nit,
            'max_rooms' => $this->maxRooms,
            'configured_rooms' => $configuredRooms,
            'available_rooms' => $this->maxRooms - $configuredRooms,
            'room_configurations' => HotelRoomConfigurationResource::collection($this->roomConfigurations),
        ];
    }
}
