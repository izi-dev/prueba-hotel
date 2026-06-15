<?php

declare(strict_types=1);

namespace Src\Domain\Exceptions;

use Exception;

/**
 * Excepción base para violaciones de reglas de negocio del dominio.
 *
 * Clase abstracta que centraliza el manejo de errores semánticos originados
 * en la capa de dominio y aplicación. Cada excepción concreta define el
 * código de estado HTTP que debe propagarse hacia la capa de presentación,
 * permitiendo traducir fallos de negocio en respuestas API coherentes sin
 * acoplar el dominio a frameworks web.
 */
abstract class DomainException extends Exception
{
    /**
     * Obtiene el código de estado HTTP asociado a la violación de regla de negocio.
     *
     * @return int Código de estado HTTP (por ejemplo, 404 para recursos no encontrados, 422 para errores de validación de dominio).
     */
    abstract public function statusCode(): int;
}
