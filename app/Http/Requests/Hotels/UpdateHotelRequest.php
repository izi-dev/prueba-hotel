<?php

declare(strict_types=1);

namespace App\Http\Requests\Hotels;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Solicitud HTTP para actualizar un hotel.
 *
 * Valida los datos de entrada del endpoint `PUT /api/v1/hotels/{id}`.
 */
final class UpdateHotelRequest extends FormRequest
{
    /**
     * Determina si el usuario autenticado puede realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para los campos del hotel.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'nit' => ['required', 'string', 'max:20', 'regex:/^\d+-\d$/'],
            'max_rooms' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Mensajes de error personalizados para la validación.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nit.regex' => 'El NIT debe tener el formato 12345678-9.',
        ];
    }
}
