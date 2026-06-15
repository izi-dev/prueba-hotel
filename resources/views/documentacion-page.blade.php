<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — Documentación</title>
    @vite(['resources/css/app.css'])
    <style>
        .prose-doc h1 { font-size: 1.875rem; font-weight: 700; margin: 1.5rem 0 1rem; }
        .prose-doc h2 { font-size: 1.375rem; font-weight: 600; margin: 1.5rem 0 0.75rem; }
        .prose-doc h3 { font-size: 1.125rem; font-weight: 600; margin: 1rem 0 0.5rem; }
        .prose-doc p, .prose-doc li { line-height: 1.7; margin: 0.5rem 0; }
        .prose-doc ul, .prose-doc ol { padding-left: 1.5rem; margin: 0.75rem 0; }
        .prose-doc code { background: #e2e8f0; padding: 0.125rem 0.375rem; border-radius: 0.25rem; font-size: 0.875em; }
        .prose-doc pre { background: #1e293b; color: #e2e8f0; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; margin: 1rem 0; }
        .prose-doc pre code { background: transparent; padding: 0; color: inherit; }
        .prose-doc table { width: 100%; border-collapse: collapse; margin: 1rem 0; font-size: 0.875rem; }
        .prose-doc th, .prose-doc td { border: 1px solid #cbd5e1; padding: 0.5rem 0.75rem; text-align: left; }
        .prose-doc th { background: #f1f5f9; }
        .prose-doc a { color: #4338ca; text-decoration: underline; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-4xl items-center justify-between px-6 py-4">
            <a href="{{ url('/documentacion') }}" class="text-sm font-medium text-indigo-700 hover:text-indigo-900">← Documentación</a>
            <a href="{{ url('/app') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">App</a>
        </div>
    </header>

    <article class="prose-doc mx-auto max-w-4xl px-6 py-10">
        {!! $content !!}
    </article>
</body>
</html>
