<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documentación — Gestión Hotelera</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-4xl items-center justify-between px-6 py-4">
            <a href="{{ url('/') }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-900">← Inicio</a>
            <a href="{{ url('/app') }}" class="rounded-md bg-indigo-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-800">
                Ir a la app
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-4xl px-6 py-10">
        <h1 class="text-2xl font-bold text-slate-900">Documentación del proyecto</h1>
        <p class="mt-2 text-slate-600">Referencias técnicas del sistema de gestión hotelera.</p>

        <div class="mt-8 grid gap-4 sm:grid-cols-2">
            <a href="{{ url('/documentacion/proyecto') }}"
               class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md">
                <h2 class="font-semibold text-indigo-900">README del proyecto</h2>
                <p class="mt-2 text-sm text-slate-600">Instalación, arquitectura, criterios de negocio y endpoints.</p>
            </a>
            <a href="{{ url('/docs/api') }}"
               class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md">
                <h2 class="font-semibold text-indigo-900">API interactiva (Scramble)</h2>
                <p class="mt-2 text-sm text-slate-600">Explora y prueba los endpoints REST en `/api/v1`.</p>
            </a>
            <a href="{{ url('/documentacion/uml') }}"
               class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md">
                <h2 class="font-semibold text-indigo-900">Diagramas UML</h2>
                <p class="mt-2 text-sm text-slate-600">Modelo de dominio, capas y flujos de casos de uso.</p>
            </a>
            <a href="{{ url('/documentacion/despliegue') }}"
               class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md">
                <h2 class="font-semibold text-indigo-900">Despliegue</h2>
                <p class="mt-2 text-sm text-slate-600">Docker, Dokploy y configuración en producción.</p>
            </a>
            <a href="{{ url('/documentacion/openapi') }}"
               class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md">
                <h2 class="font-semibold text-indigo-900">OpenAPI (YAML)</h2>
                <p class="mt-2 text-sm text-slate-600">Especificación manual de la API REST.</p>
            </a>
            <a href="{{ url('/docs/api.json') }}"
               class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md">
                <h2 class="font-semibold text-indigo-900">OpenAPI (JSON)</h2>
                <p class="mt-2 text-sm text-slate-600">Exportación generada automáticamente por Scramble.</p>
            </a>
        </div>
    </main>
</body>
</html>
