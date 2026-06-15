<?php

declare(strict_types=1);

namespace Src\Domain\Exceptions;

/**
 * Indica que la cantidad total de habitaciones supera el máximo permitido del hotel.
 *
 * Se lanza cuando la suma de cantidades en las configuraciones de un
 * {@see \Src\Domain\Entities\Hotel} excede el valor de {@see \Src\Domain\Entities\Hotel::$maxRooms},
 * violando la invariante de capacidad del agregado.
 */
final class RoomCountExceededException extends DomainException
{
    /**
     * @param  int  $maxRooms  Límite máximo de habitaciones definido para el hotel.
     * @param  int  $requestedTotal  Cantidad total de habitaciones solicitada que supera el límite.
     */
    public function __construct(int $maxRooms, int $requestedTotal)
    {
        parent::__construct(
            sprintf('La cantidad total de habitaciones (%d) supera el máximo permitido (%d).', $requestedTotal, $maxRooms)
        );
    }

    /**
     * @return int Código 422 (Unprocessable Entity), indicando una violación de la regla de capacidad del hotel.
     */
    public function statusCode(): int
    {
        return 422;
    }
}
