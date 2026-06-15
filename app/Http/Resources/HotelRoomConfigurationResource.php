<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Domain\Entities\HotelRoomConfiguration;

/**
 * Recurso JSON que representa una configuración de habitación de un hotel.
 *
 * Transforma la entidad de dominio {@see HotelRoomConfiguration}
 * al formato expuesto por la API.
 *
 * @mixin HotelRoomConfiguration
 */
final class HotelRoomConfigurationResource extends JsonResource
{
    /**
     * Serializa la configuración de habitación a un arreglo asociativo para la respuesta HTTP.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hotel_id' => $this->hotelId,
            'room_type_id' => $this->roomTypeId,
            'room_type_name' => $this->roomTypeName,
            'accommodation_id' => $this->accommodationId,
            'accommodation_name' => $this->accommodationName,
            'quantity' => $this->quantity,
        ];
    }
}
