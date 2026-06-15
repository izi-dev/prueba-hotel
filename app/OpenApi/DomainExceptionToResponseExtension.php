<?php

declare(strict_types=1);

namespace App\OpenApi;

use Dedoc\Scramble\Extensions\ExceptionToResponseExtension;
use Dedoc\Scramble\Support\Generator\Reference;
use Dedoc\Scramble\Support\Generator\Response;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\Types as OpenApiTypes;
use Dedoc\Scramble\Support\Type\ObjectType;
use Dedoc\Scramble\Support\Type\Type;
use Illuminate\Support\Str;
use Src\Domain\Exceptions\DuplicateHotelException;
use Src\Domain\Exceptions\DuplicateRoomConfigurationException;
use Src\Domain\Exceptions\HotelNotFoundException;
use Src\Domain\Exceptions\InvalidRoomTypeAccommodationException;
use Src\Domain\Exceptions\RoomConfigurationNotFoundException;
use Src\Domain\Exceptions\RoomCountExceededException;

/**
 * Documenta en OpenAPI las excepciones de dominio como respuestas JSON con `message`.
 */
final class DomainExceptionToResponseExtension extends ExceptionToResponseExtension
{
    /** @var array<class-string, array{status: int, description: string}> */
    private const array EXCEPTIONS = [
        HotelNotFoundException::class => [
            'status' => 404,
            'description' => 'El hotel solicitado no existe.',
        ],
        RoomConfigurationNotFoundException::class => [
            'status' => 404,
            'description' => 'La configuración de habitación no existe o no pertenece al hotel indicado.',
        ],
        DuplicateHotelException::class => [
            'status' => 422,
            'description' => 'Ya existe otro hotel registrado con el mismo NIT.',
        ],
        DuplicateRoomConfigurationException::class => [
            'status' => 422,
            'description' => 'Ya existe una configuración con el mismo tipo de habitación y acomodación en este hotel.',
        ],
        InvalidRoomTypeAccommodationException::class => [
            'status' => 422,
            'description' => 'La acomodación seleccionada no es válida para el tipo de habitación indicado.',
        ],
        RoomCountExceededException::class => [
            'status' => 422,
            'description' => 'La cantidad de habitaciones supera el máximo permitido para el hotel.',
        ],
    ];

    public function shouldHandle(Type $type): bool
    {
        return $type instanceof ObjectType
            && array_key_exists($type->name, self::EXCEPTIONS);
    }

    public function toResponse(Type $type): Response
    {
        /** @var ObjectType $type */
        $config = self::EXCEPTIONS[$type->name];

        $bodyType = (new OpenApiTypes\ObjectType)
            ->addProperty(
                'message',
                (new OpenApiTypes\StringType)
                    ->setDescription('Descripción legible del error de negocio.')
            )
            ->setRequired(['message']);

        return Response::make($config['status'])
            ->setDescription($config['description'])
            ->setContent(
                'application/json',
                Schema::fromType($bodyType),
            );
    }

    public function reference(ObjectType $type): Reference
    {
        return new Reference('responses', Str::start($type->name, '\\'), $this->components);
    }
}
