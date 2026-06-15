<?php

declare(strict_types=1);

/**
 * Configuración global del framework de pruebas Pest.
 *
 * - Extiende `Tests\TestCase` para Feature y Unit.
 * - Aplica `RefreshDatabase` para aislar cada test con base de datos limpia.
 * - Registra matchers personalizados reutilizables en toda la suite.
 */
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature', 'Unit');

/**
 * Matcher personalizado: verifica que el valor sea exactamente 1.
 */
expect()->extend('toBeOne', fn () => $this->toBe(1));
