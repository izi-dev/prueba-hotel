<?php

declare(strict_types=1);

/**
 * Verifica que invitados puedan acceder a la documentación Scramble en producción.
 */
use Illuminate\Support\Facades\Gate;

it('allows guests through the viewApiDocs gate', function (): void {
    expect(Gate::allows('viewApiDocs'))->toBeTrue();
});
