<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Most-Starred PHP Projects</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <div class="mx-auto max-w-4xl px-6 py-16">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-semibold">Most-Starred Public PHP Projects</h1>
                <p class="mt-2 text-slate-400">Stored in the database and refreshed from the GitHub public API.</p>
            </div>
            <form method="POST" action="{{ route('github.projects.refresh', [], false) }}">
                @csrf
                <button type="submit" class="rounded-lg border border-slate-700 bg-slate-800 px-4 py-2 text-sm font-medium text-slate-100 transition hover:bg-slate-700">
                    Refresh from GitHub
                </button>
            </form>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-lg border border-emerald-800 bg-emerald-950/60 px-4 py-3 text-sm text-emerald-300">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-8 overflow-hidden rounded-xl border border-slate-800 bg-slate-900 shadow-lg">
            <table class="min-w-full divide-y divide-slate-800">
                <thead class="bg-slate-800/80">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium uppercase tracking-wide text-slate-300">Project</th>
                        <th class="px-4 py-3 text-left text-sm font-medium uppercase tracking-wide text-slate-300">Stars</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($projects as $project)
                        <tr class="hover:bg-slate-800/60">
                            <td class="px-4 py-3 text-sm text-slate-200">
                                <a href="{{ route('github.projects.show', $project, false) }}" class="font-medium text-sky-400 hover:text-sky-300">
                                    {{ $project->name }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-200">{{ number_format($project->num_stars) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-6 text-center text-sm text-slate-400">No projects found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
