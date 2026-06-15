<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Dedoc\Scramble\Attributes\SchemaName;
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
#[SchemaName('Hotel')]
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
            /** Identificador único del hotel. */
            'id' => $this->id,
            /** Nombre comercial. */
            'name' => $this->name,
            /** Dirección física. */
            'address' => $this->address,
            /** ID de la ciudad asociada. */
            'city_id' => $this->cityId,
            /** Nombre de la ciudad (desnormalizado). */
            'city_name' => $this->cityName,
            /** NIT único con formato `12345678-9`. */
            'nit' => $this->nit,
            /** Cupo máximo de habitaciones del hotel. */
            'max_rooms' => $this->maxRooms,
            /** Suma de cantidades en todas las configuraciones. */
            'configured_rooms' => $configuredRooms,
            /** Habitaciones aún disponibles para configurar (`max_rooms - configured_rooms`). */
            'available_rooms' => $this->maxRooms - $configuredRooms,
            /** Detalle de configuraciones por tipo y acomodación. */
            'room_configurations' => HotelRoomConfigurationResource::collection($this->roomConfigurations),
        ];
    }
}
