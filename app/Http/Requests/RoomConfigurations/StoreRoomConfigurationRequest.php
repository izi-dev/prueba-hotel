<?php

declare(strict_types=1);

namespace App\Http\Requests\RoomConfigurations;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Solicitud HTTP para crear una configuración de habitación.
 *
 * Valida los datos de entrada del endpoint
 * `POST /api/v1/hotels/{hotelId}/room-configurations`.
 */
final class StoreRoomConfigurationRequest extends FormRequest
{
    /**
     * Determina si el usuario autenticado puede realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para los campos de la configuración de habitación.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'accommodation_id' => ['required', 'integer', 'exists:accommodations,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
