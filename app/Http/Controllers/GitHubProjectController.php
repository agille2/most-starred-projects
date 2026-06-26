<?php

namespace App\Http\Controllers;

use App\Models\GitHubProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class GitHubProjectController extends Controller
{
    public function index(): View
    {
        $this->syncProjects();

        $projects = GitHubProject::query()
            ->orderByDesc('num_stars')
            ->get();

        return view('github-projects', compact('projects'));
    }

    public function refresh(): RedirectResponse
    {
        $this->syncProjects();

        return redirect()->route('github.projects')->with('status', 'GitHub data refreshed.');
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
            'per_page' => 10,
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
