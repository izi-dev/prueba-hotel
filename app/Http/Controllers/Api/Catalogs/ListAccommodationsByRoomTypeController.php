<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccommodationCollection;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Src\Application\Catalogs\ListAccommodationsByRoomType\ListAccommodationsByRoomTypeHandler;

#[Group(name: 'Catálogos', description: 'Datos maestros de solo lectura.', weight: 10)]
final class ListAccommodationsByRoomTypeController extends Controller
{
    public function __construct(
        private readonly ListAccommodationsByRoomTypeHandler $handler,
    ) {}

    #[Endpoint(
        operationId: 'catalogs.roomTypes.accommodations',
        title: 'Acomodaciones por tipo de habitación',
        description: 'Lista solo las acomodaciones permitidas para el tipo indicado según las reglas de negocio (Estándar→Sencilla/Doble, Junior→Triple/Cuádruple, Suite→Sencilla/Doble/Triple).',
    )]
    public function __invoke(
        #[PathParameter('roomTypeId', description: 'Identificador del tipo de habitación.', example: 1)]
        int $roomTypeId,
    ): AccommodationCollection {
        return new AccommodationCollection($this->handler->handle(roomTypeId: $roomTypeId));
    }
}
