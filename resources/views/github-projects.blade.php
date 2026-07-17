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

        <div class="mt-8 flex flex-wrap items-center justify-between gap-3">
            <div class="text-sm text-slate-400">Showing {{ $perPage }} items per page</div>
            <form method="GET" action="{{ route('github.projects', [], false) }}" class="flex items-center gap-2">
                <label for="per_page" class="text-sm text-slate-400">Page size</label>
                <select id="per_page" name="per_page" onchange="this.form.submit()" class="rounded-lg border border-slate-700 bg-slate-900 px-3 py-2 text-sm text-slate-100">
                    <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                </select>
            </form>
        </div>

        <div class="mt-4 overflow-hidden rounded-xl border border-slate-800 bg-slate-900 shadow-lg">
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
                                <a href="{{ route('github.projects.show', $project, false) }}" data-details-url="{{ route('github.projects.show.json', $project, false) }}" class="project-detail-link font-medium text-sky-400 hover:text-sky-300">
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

            @if ($projects->hasPages())
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-800 bg-slate-950/60 px-4 py-3 text-sm text-slate-300">
                    <div>
                        Showing {{ $projects->firstItem() }}-{{ $projects->lastItem() }} of {{ $projects->total() }} projects
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($projects->onFirstPage())
                            <span class="rounded border border-slate-700 px-3 py-1 text-slate-500">Previous</span>
                        @else
                            <a href="{{ $projects->previousPageUrl() }}" class="rounded border border-slate-700 px-3 py-1 hover:bg-slate-800">Previous</a>
                        @endif

                        @foreach ($projects->getUrlRange(1, $projects->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="rounded border px-3 py-1 {{ $page == $projects->currentPage() ? 'border-sky-500 bg-sky-500/20 text-sky-300' : 'border-slate-700 text-slate-300 hover:bg-slate-800' }}">
                                {{ $page }}
                            </a>
                        @endforeach

                        @if ($projects->hasMorePages())
                            <a href="{{ $projects->nextPageUrl() }}" class="rounded border border-slate-700 px-3 py-1 hover:bg-slate-800">Next</a>
                        @else
                            <span class="rounded border border-slate-700 px-3 py-1 text-slate-500">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div id="project-details-overlay"></div>
    </div>
</body>
</html>
