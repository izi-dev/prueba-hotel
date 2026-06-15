<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\RoomConfigurations;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelRoomConfigurationCollection;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Src\Application\RoomConfigurations\ListRoomConfigurations\ListRoomConfigurationsHandler;
use Src\Domain\Exceptions\HotelNotFoundException;

#[Group(name: 'Configuraciones', description: 'Configuración de habitaciones por hotel.', weight: 30)]
final class ListRoomConfigurationsController extends Controller
{
    public function __construct(
        private readonly ListRoomConfigurationsHandler $handler,
    ) {}

    /**
     * @throws HotelNotFoundException
     */
    #[Endpoint(
        operationId: 'hotels.roomConfigurations.index',
        title: 'Listar configuraciones de habitación',
        description: 'Devuelve todas las configuraciones de habitación asociadas al hotel indicado.',
    )]
    public function __invoke(
        #[PathParameter('hotelId', description: 'Identificador del hotel.', example: 1)]
        int $hotelId,
    ): HotelRoomConfigurationCollection {
        return new HotelRoomConfigurationCollection($this->handler->handle(hotelId: $hotelId));
    }
}
