<?php

declare(strict_types=1);

namespace Src\Domain\Exceptions;

/**
 * Indica que la acomodación seleccionada no es compatible con el tipo de habitación.
 *
 * Se lanza cuando las reglas de negocio que gobiernan la relación entre
 * {@see \Src\Domain\Entities\RoomType} y {@see \Src\Domain\Entities\Accommodation}
 * rechazan la combinación solicitada al crear o actualizar una configuración de habitación.
 */
final class InvalidRoomTypeAccommodationException extends DomainException
{
    /**
     * @param  string  $roomType  Nombre o identificador legible del tipo de habitación involucrado.
     * @param  string  $accommodation  Nombre o identificador legible de la acomodación rechazada.
     */
    public function __construct(string $roomType, string $accommodation)
    {
        parent::__construct(
            message: sprintf('La acomodación %s no es válida para el tipo de habitación %s.', $accommodation, $roomType),
        );
    }

    /**
     * @return int Código 422 (Unprocessable Entity), indicando una combinación inválida según las reglas de dominio.
     */
    public function statusCode(): int
    {
        return 422;
    }
}
