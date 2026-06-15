<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión Hotelera — Freddy Johanes Vargas Ramírez</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/40 via-slate-950 to-slate-950"></div>

        <div class="relative mx-auto max-w-5xl px-6 py-12 lg:py-16">
            <header class="mb-10 flex flex-col gap-4 border-b border-white/10 pb-8 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-medium uppercase tracking-widest text-indigo-300">Prueba técnica</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Sistema de Gestión Hotelera</h1>
                    <p class="mt-2 text-slate-400">API REST con arquitectura hexagonal · React · PostgreSQL</p>
                </div>
                <p class="text-sm text-slate-500">Laravel {{ app()->version() }}</p>
            </header>

            <section class="mb-12 grid gap-8 lg:grid-cols-5">
                <div class="lg:col-span-3">
                    <h2 class="text-xl font-semibold text-white">Freddy Johanes Vargas Ramírez</h2>
                    <p class="mt-1 text-sm text-indigo-300">Ingeniero de Sistemas · Senior Full Stack Software Engineer</p>

                    <div class="mt-6 space-y-4 text-sm leading-relaxed text-slate-300">
                        <p>
                            Más de <strong class="text-white">10 años de experiencia</strong> en desarrollo de software,
                            participando en proyectos para sectores fintech, banca, salud, LMS, outsourcing y plataformas empresariales.
                        </p>
                        <p>
                            Especializado en desarrollo backend, arquitecturas escalables y sistemas distribuidos con
                            <strong class="text-white">PHP, Go, Node.js, C#, Java, Python y AWS</strong>. Experiencia en
                            microservicios, APIs REST, arquitectura hexagonal, Clean Architecture, SOLID, CI/CD, TDD y procesos asíncronos.
                        </p>
                        <p>
                            He participado en startups y compañías como
                            <strong class="text-white">UBITS, EVERTEC, Merqueo, PersonalSoft y Zulu</strong>,
                            contribuyendo en productos, modernización de plataformas y liderazgo técnico.
                        </p>
                        <p>
                            Actualmente enfocado en <strong class="text-white">inteligencia artificial aplicada al desarrollo</strong>,
                            automatización, agentes IA y AI-assisted development para optimizar productividad, calidad y velocidad de entrega.
                        </p>
                        <p>
                            También he desarrollado proyectos open source con miles de descargas. Me caracterizo por
                            liderazgo técnico, trabajo en equipo, comunicación efectiva, adaptabilidad y orientación a resultados.
                        </p>
                    </div>
                </div>

                <aside class="lg:col-span-2">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Accesos rápidos</h3>
                        <nav class="mt-4 flex flex-col gap-3">
                            <a href="{{ url('/app') }}"
                               class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-900/30 transition hover:bg-indigo-500">
                                Entrar a la aplicación
                            </a>
                            <a href="{{ url('/docs/api') }}"
                               class="rounded-lg border border-white/15 px-4 py-3 text-sm font-medium text-white transition hover:border-indigo-400/50 hover:bg-white/5">
                                Documentación API (Scramble)
                            </a>
                            <a href="{{ url('/documentacion') }}"
                               class="rounded-lg border border-white/15 px-4 py-3 text-sm font-medium text-white transition hover:border-indigo-400/50 hover:bg-white/5">
                                Documentación del proyecto
                            </a>
                            <a href="{{ url('/docs/api.json') }}"
                               class="rounded-lg border border-white/10 px-4 py-3 text-xs text-slate-400 transition hover:text-slate-200">
                                OpenAPI JSON
                            </a>
                            <a href="https://github.com/izi-dev/prueba-hotel"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="rounded-lg border border-white/15 px-4 py-3 text-sm font-medium text-white transition hover:border-indigo-400/50 hover:bg-white/5">
                                Código fuente (GitHub)
                            </a>
                            <a href="https://github.com/izi-dev/prueba-hotel/actions"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="rounded-lg border border-white/15 px-4 py-3 text-sm font-medium text-white transition hover:border-indigo-400/50 hover:bg-white/5">
                                Pipeline CI (GitHub Actions)
                            </a>
                        </nav>
                    </div>

                    <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 p-6 text-xs text-slate-400">
                        <p class="font-medium text-slate-300">Repositorio y CI</p>
                        <p class="mt-3 leading-relaxed">
                            El código completo está en
                            <a href="https://github.com/izi-dev/prueba-hotel"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="text-indigo-300 underline decoration-indigo-300/40 underline-offset-2 hover:text-indigo-200">
                                GitHub
                            </a>.
                            Cada push a <code class="rounded bg-white/10 px-1 py-0.5 text-slate-300">main</code>
                            dispara el workflow de
                            <a href="https://github.com/izi-dev/prueba-hotel/actions"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="text-indigo-300 underline decoration-indigo-300/40 underline-offset-2 hover:text-indigo-200">
                                GitHub Actions
                            </a>:
                            auditoría Composer, Pint, PHPStan, Rector, Pest (backend) y
                            <code class="rounded bg-white/10 px-1 py-0.5 text-slate-300">npm ci</code> +
                            build de Vite (frontend).
                        </p>
                    </div>

                    <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 p-6 text-xs text-slate-400">
                        <p class="font-medium text-slate-300">Stack de este proyecto</p>
                        <ul class="mt-3 space-y-1">
                            <li>Laravel 13 · PHP 8.4 · Pest · PHPStan · Pint · Rector</li>
                            <li>React · Vite · Tailwind CSS</li>
                            <li>PostgreSQL · Docker · GitHub Actions · Dokploy</li>
                        </ul>
                    </div>
                </aside>
            </section>

            <footer class="border-t border-white/10 pt-6 text-center text-xs text-slate-500">
                Desarrollado por Freddy Johanes Vargas Ramírez · {{ date('Y') }}
            </footer>
        </div>
    </div>
</body>
</html>
