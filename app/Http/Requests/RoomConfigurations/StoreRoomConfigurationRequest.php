<?php

declare(strict_types=1);

namespace App\Http\Requests\RoomConfigurations;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Solicitud HTTP para crear una configuración de habitación.
 */
final class StoreRoomConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            /** Tipo de habitación (`GET /api/v1/room-types`). @example 1 */
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            /** Acomodación compatible con el tipo (`GET /api/v1/room-types/{id}/accommodations`). @example 2 */
            'accommodation_id' => ['required', 'integer', 'exists:accommodations,id'],
            /** Cantidad de habitaciones de esta combinación. @example 10 */
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
