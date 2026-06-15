<?php

declare(strict_types=1);

namespace Src\Application\Shared;

use Src\Domain\Entities\Accommodation;
use Src\Domain\Entities\RoomType;
use Src\Domain\Exceptions\InvalidRoomTypeAccommodationException;
use Src\Domain\Repositories\AccommodationRepositoryInterface;
use Src\Domain\Repositories\RoomTypeRepositoryInterface;

/**
 * Servicio de aplicación que valida la compatibilidad entre tipos de habitación y acomodaciones.
 *
 * Orquesta los repositorios de catálogo para comprobar que la combinación solicitada
 * esté permitida según las relaciones definidas en los datos sembrados.
 */
final readonly class RoomTypeAccommodationRules
{
    /**
     * @param  RoomTypeRepositoryInterface  $roomTypeRepository  Repositorio de tipos de habitación.
     * @param  AccommodationRepositoryInterface  $accommodationRepository  Repositorio de acomodaciones.
     */
    public function __construct(
        private RoomTypeRepositoryInterface $roomTypeRepository,
        private AccommodationRepositoryInterface $accommodationRepository,
    ) {}

    /**
     * Comprueba que la acomodación sea válida para el tipo de habitación indicado.
     *
     * Si alguno de los identificadores no corresponde a una entidad existente,
     * la validación se omite silenciosamente (la capa de validación de entrada
     * o el repositorio se encargarán de ese caso).
     *
     * @param  int  $roomTypeId  Identificador del tipo de habitación.
     * @param  int  $accommodationId  Identificador de la acomodación.
     *
     * @throws InvalidRoomTypeAccommodationException Si la acomodación no está permitida para el tipo de habitación.
     */
    public function assertValid(int $roomTypeId, int $accommodationId): void
    {
        $roomType = $this->roomTypeRepository->findById(id: $roomTypeId);
        $accommodation = $this->accommodationRepository->findById(id: $accommodationId);

        // Si el tipo o la acomodación no existen, no se aplica la regla de compatibilidad aquí.
        if (! $roomType instanceof RoomType || ! $accommodation instanceof Accommodation) {
            return;
        }

        // Obtener las acomodaciones permitidas para el tipo y extraer sus identificadores.
        $allowedAccommodations = $this->accommodationRepository->findByRoomTypeId(roomTypeId: $roomTypeId);
        $allowedIds = array_map(
            callback: fn (Accommodation $item): int => $item->id,
            array: $allowedAccommodations,
        );

        // Lanzar excepción de dominio si la acomodación solicitada no está en la lista permitida.
        if (! in_array(needle: $accommodationId, haystack: $allowedIds, strict: true)) {
            throw new InvalidRoomTypeAccommodationException(
                roomType: $roomType->name,
                accommodation: $accommodation->name,
            );
        }
    }
}
