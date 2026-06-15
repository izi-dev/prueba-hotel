<?php

declare(strict_types=1);

namespace Src\Domain\Exceptions;

/**
 * Indica que ya existe un hotel registrado con el mismo NIT.
 *
 * Se lanza cuando un caso de uso de creación o actualización de hotel
 * detecta que el NIT proporcionado ya está asignado a otro registro,
 * violando la invariante de unicidad del agregado {@see \Src\Domain\Entities\Hotel}.
 */
final class DuplicateHotelException extends DomainException
{
    /**
     * @param  string  $nit  Número de identificación tributaria duplicado que provocó el conflicto.
     */
    public function __construct(string $nit)
    {
        parent::__construct(sprintf('Ya existe un hotel registrado con el NIT %s.', $nit));
    }

    /**
     * @return int Código 422 (Unprocessable Entity), indicando un conflicto de regla de negocio.
     */
    public function statusCode(): int
    {
        return 422;
    }
}
