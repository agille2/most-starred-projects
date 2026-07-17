<?php

namespace App\Http\Controllers;

use App\Models\GitHubProject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class GitHubProjectController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = in_array((int) $request->query('per_page', 5), [5, 10, 25], true)
            ? (int) $request->query('per_page', 5)
            : 5;

        $projects = GitHubProject::query()
            ->orderByDesc('num_stars')
            ->paginate($perPage)->withPath('')
            ->appends($request->query());

        return view('github-projects', compact('projects', 'perPage'));
    }

    public function refresh(): RedirectResponse
    {
        $this->syncProjects();

        return redirect()->route('github.projects')->with('status', 'GitHub data refreshed.');
    }

    public function showJson(GitHubProject $project): JsonResponse
    {
        return response()->json([
            'project' => [
                'name' => $project->name,
                'description' => $project->description,
                'url' => $project->url,
                'repoId' => $project->repoId,
                'num_stars' => $project->num_stars,
                'created_date' => optional($project->created_date)->format('Y-m-d'),
                'last_push_date' => optional($project->last_push_date)->format('Y-m-d'),
            ],
        ]);
    }

    public function show(GitHubProject $project): View
    {
        return view('github-project-details', compact('project'));
    }

    protected function syncProjects(): void
    {
        $response = Http::get('https://api.github.com/search/repositories', [
            'q' => 'language:php',
            'sort' => 'stars',
            'order' => 'desc',
            'per_page' => 25,
        ]);

        if (! $response->successful()) {
            return;
        }

        $items = collect($response->json('items', []));

        $items->each(function ($item) {
            $repoId = $item['id'] ?? null;

            if ($repoId === null) {
                return;
            }

            GitHubProject::query()->updateOrCreate(
                ['repoId' => $repoId],
                [
                    'name' => $item['full_name'] ?? $item['name'] ?? 'Unknown',
                    'url' => $item['html_url'] ?? 'Unknown',
                    'created_date' => $item['created_at'] ?? null,
                    'last_push_date' => $item['pushed_at'] ?? null,
                    'description' => $item['description'] ?? null,
                    'num_stars' => $item['stargazers_count'] ?? 0,
                ]
            );
        });
    }
}
