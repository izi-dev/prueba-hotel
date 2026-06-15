<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Catalogs;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomTypeCollection;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Src\Application\Catalogs\ListRoomTypes\ListRoomTypesHandler;

#[Group(name: 'Catálogos', description: 'Datos maestros de solo lectura.', weight: 10)]
final class ListRoomTypesController extends Controller
{
    public function __construct(
        private readonly ListRoomTypesHandler $handler,
    ) {}

    #[Endpoint(
        operationId: 'catalogs.roomTypes.index',
        title: 'Listar tipos de habitación',
        description: 'Devuelve los tipos de habitación del sistema: Estándar, Junior y Suite.',
    )]
    public function __invoke(): RoomTypeCollection
    {
        return new RoomTypeCollection($this->handler->handle());
    }
}
