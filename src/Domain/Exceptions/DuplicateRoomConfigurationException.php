<?php

declare(strict_types=1);

namespace Src\Domain\Exceptions;

/**
 * Indica que ya existe una configuración de habitación duplicada para un hotel.
 *
 * Se lanza cuando se intenta registrar o modificar una
 * {@see \Src\Domain\Entities\HotelRoomConfiguration} cuya combinación de
 * hotel, tipo de habitación y acomodación ya está presente en el inventario,
 * violando la restricción de unicidad por combinación.
 */
final class DuplicateRoomConfigurationException extends DomainException
{
    /**
     * Construye la excepción con un mensaje predefinido que describe el conflicto de duplicidad.
     */
    public function __construct()
    {
        parent::__construct(
            'Ya existe una configuración con el mismo tipo de habitación y acomodación para este hotel.'
        );
    }

    /**
     * @return int Código 422 (Unprocessable Entity), indicando un conflicto de regla de negocio.
     */
    public function statusCode(): int
    {
        return 422;
    }
}
