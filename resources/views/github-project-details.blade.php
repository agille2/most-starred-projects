<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="mx-auto max-w-4xl px-6 py-16">
        <a href="{{ route('github.projects', [], false) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-700 bg-slate-900 px-4 py-2 text-sm font-medium text-sky-300 transition hover:bg-slate-800">
            ← Back to projects
        </a>

        <section class="mt-8 rounded-3xl border border-slate-800 bg-slate-900 p-8 shadow-xl shadow-slate-950/20">
            <div class="flex flex-col gap-6">
                <div>
                    <h1 class="text-4xl font-semibold text-white">{{ $project->name }}</h1>
                    <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-300">
                        {{ $project->description ?: 'No description available.' }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ $project->url }}" target="_blank" rel="noopener noreferrer" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-sky-400">
                        View on GitHub
                    </a>
                    <span class="text-sm text-slate-400">Repo ID: {{ $project->repoId }}</span>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-6">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Stars</p>
                        <p class="mt-3 text-3xl font-semibold text-white">{{ number_format($project->num_stars) }}</p>
                    </div>

                    <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-6">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Created</p>
                        <p class="mt-3 text-3xl font-semibold text-white">{{ $project->created_date ? $project->created_date->format('Y-m-d') : 'Unknown' }}</p>
                    </div>

                    <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-6">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Last pushed</p>
                        <p class="mt-3 text-3xl font-semibold text-white">{{ $project->last_push_date ? $project->last_push_date->format('Y-m-d') : 'Unknown' }}</p>
                    </div>

                    <div class="rounded-3xl border border-slate-800 bg-slate-950/80 p-6">
                        <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Repository ID</p>
                        <p class="mt-3 text-3xl font-semibold text-white">{{ $project->repoId }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>