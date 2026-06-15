<?php

declare(strict_types=1);

namespace Src\Domain\Exceptions;

/**
 * Indica que no se encontró una configuración de habitación con el identificador solicitado.
 *
 * Se lanza cuando un caso de uso requiere una
 * {@see \Src\Domain\Entities\HotelRoomConfiguration} existente y el repositorio
 * no devuelve ningún registro para el id proporcionado.
 */
final class RoomConfigurationNotFoundException extends DomainException
{
    /**
     * @param  int  $configurationId  Identificador de la configuración de habitación no encontrada.
     */
    public function __construct(int $configurationId)
    {
        parent::__construct(sprintf('Configuración de habitación con id %d no encontrada.', $configurationId));
    }

    /**
     * @return int Código 404 (Not Found), indicando que el recurso solicitado no existe.
     */
    public function statusCode(): int
    {
        return 404;
    }
}
