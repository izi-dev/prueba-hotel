<?php

declare(strict_types=1);

namespace Src\Domain\Exceptions;

/**
 * Indica que no se encontró un hotel con el identificador solicitado.
 *
 * Se lanza cuando un caso de uso requiere un {@see \Src\Domain\Entities\Hotel}
 * existente y el repositorio no devuelve ningún registro para el id proporcionado.
 */
final class HotelNotFoundException extends DomainException
{
    /**
     * @param  int  $hotelId  Identificador del hotel que no fue encontrado en persistencia.
     */
    public function __construct(int $hotelId)
    {
        parent::__construct(sprintf('Hotel con id %d no encontrado.', $hotelId));
    }

    /**
     * @return int Código 404 (Not Found), indicando que el recurso solicitado no existe.
     */
    public function statusCode(): int
    {
        return 404;
    }
}
