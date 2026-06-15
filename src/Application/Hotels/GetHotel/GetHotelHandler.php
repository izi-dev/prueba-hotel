<?php

declare(strict_types=1);

namespace Src\Application\Hotels\GetHotel;

use Src\Domain\Entities\Hotel;
use Src\Domain\Exceptions\HotelNotFoundException;
use Src\Domain\Repositories\HotelRepositoryInterface;

/**
 * Caso de uso para obtener el detalle de un hotel por su identificador.
 */
final readonly class GetHotelHandler
{
    /**
     * @param  HotelRepositoryInterface  $hotelRepository  Repositorio de acceso a hoteles.
     */
    public function __construct(
        private HotelRepositoryInterface $hotelRepository,
    ) {}

    /**
     * Recupera un hotel existente.
     *
     * @param  int  $id  Identificador del hotel solicitado.
     * @return Hotel Entidad de dominio con sus datos y relaciones cargadas.
     *
     * @throws HotelNotFoundException Si no existe un hotel con el identificador indicado.
     */
    public function handle(int $id): Hotel
    {
        $hotel = $this->hotelRepository->findById(id: $id);

        throw_if(! $hotel instanceof Hotel, HotelNotFoundException::class, hotelId: $id);

        return $hotel;
    }
}
