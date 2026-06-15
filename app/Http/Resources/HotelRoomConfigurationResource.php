<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Dedoc\Scramble\Attributes\SchemaName;
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
#[SchemaName('HotelRoomConfiguration')]
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
            /** Identificador de la configuración. */
            'id' => $this->id,
            /** Hotel al que pertenece. */
            'hotel_id' => $this->hotelId,
            /** Tipo de habitación configurado. */
            'room_type_id' => $this->roomTypeId,
            /** Nombre del tipo de habitación. */
            'room_type_name' => $this->roomTypeName,
            /** Acomodación configurada. */
            'accommodation_id' => $this->accommodationId,
            /** Nombre de la acomodación. */
            'accommodation_name' => $this->accommodationName,
            /** Cantidad de habitaciones de esta combinación. */
            'quantity' => $this->quantity,
        ];
    }
}
