<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Sirve la documentación del proyecto en formato HTML o archivos estáticos.
 */
final class DocumentacionController extends Controller
{
    /** @var array<string, array{path: string, title: string}> */
    private const PAGES = [
        'proyecto' => ['path' => 'README.md', 'title' => 'README del proyecto'],
        'uml' => ['path' => 'docs/uml.md', 'title' => 'Diagramas UML'],
        'despliegue' => ['path' => 'docs/deployment.md', 'title' => 'Despliegue'],
    ];

    /**
     * Índice de documentación con enlaces a cada recurso.
     */
    public function index(): View
    {
        return view('documentacion');
    }

    /**
     * Renderiza un archivo Markdown del repositorio como HTML.
     */
    public function show(string $page): View
    {
        $config = self::PAGES[$page] ?? null;

        if ($config === null) {
            throw new NotFoundHttpException;
        }

        $fullPath = base_path($config['path']);

        if (! is_file($fullPath)) {
            throw new NotFoundHttpException;
        }

        return view('documentacion-page', [
            'title' => $config['title'],
            'content' => Str::markdown(file_get_contents($fullPath) ?: ''),
        ]);
    }

    /**
     * Descarga o visualiza el archivo OpenAPI YAML.
     */
    public function openapi(): BinaryFileResponse
    {
        $path = base_path('docs/openapi.yaml');

        if (! is_file($path)) {
            throw new NotFoundHttpException;
        }

        return response()->file($path, [
            'Content-Type' => 'application/yaml',
        ]);
    }
}
