<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GitHubProjectsTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_loads_projects_from_github_into_database_and_displays_summary(): void
    {
        Http::fake([
            'https://api.github.com/search/repositories*' => Http::response([
                'items' => [
                    [
                        'id' => 101,
                        'full_name' => 'laravel/framework',
                        'html_url' => 'https://github.com/laravel/framework',
                        'created_at' => '2020-01-01T00:00:00Z',
                        'pushed_at' => '2024-01-01T00:00:00Z',
                        'description' => 'The Laravel framework.',
                        'stargazers_count' => 1234,
                    ],
                ],
            ], 200),
        ]);

        $response = $this->get('/github-projects');

        $response->assertOk();
        $response->assertSee('laravel/framework');
        $response->assertSee('1,234');
        $this->assertDatabaseHas('github_php_projects', [
            'repoId' => 101,
            'name' => 'laravel/framework',
            'num_stars' => 1234,
        ]);
    }

    public function test_refresh_route_updates_matching_projects_and_inserts_new_ones(): void
    {
        Http::fakeSequence()
            ->push([
                'items' => [[
                    'id' => 101,
                    'full_name' => 'laravel/framework',
                    'html_url' => 'https://github.com/laravel/framework',
                    'created_at' => '2020-01-01T00:00:00Z',
                    'pushed_at' => '2024-01-01T00:00:00Z',
                    'description' => 'The Laravel framework.',
                    'stargazers_count' => 1234,
                ]],
            ], 200)
            ->push([
                'items' => [[
                    'id' => 202,
                    'full_name' => 'php/php-src',
                    'html_url' => 'https://github.com/php/php-src',
                    'created_at' => '2010-01-01T00:00:00Z',
                    'pushed_at' => '2024-01-02T00:00:00Z',
                    'description' => 'PHP source code.',
                    'stargazers_count' => 999,
                ]],
            ], 200);

        $this->get('/github-projects');

        $response = $this->post('/github-projects/refresh');

        $response->assertRedirect();
        $this->assertDatabaseHas('github_php_projects', [
            'repoId' => 101,
            'name' => 'laravel/framework',
            'num_stars' => 1234,
        ]);
        $this->assertDatabaseHas('github_php_projects', [
            'repoId' => 202,
            'name' => 'php/php-src',
            'num_stars' => 999,
        ]);
    }

    public function test_existing_projects_are_updated_by_repo_id_and_new_ones_are_inserted(): void
    {
        \App\Models\GitHubProject::query()->create([
            'repoId' => 101,
            'name' => 'old/name',
            'url' => 'https://example.com/old',
            'num_stars' => 1,
        ]);

        Http::fake([
            'https://api.github.com/search/repositories*' => Http::response([
                'items' => [
                    [
                        'id' => 101,
                        'full_name' => 'laravel/framework',
                        'html_url' => 'https://github.com/laravel/framework',
                        'created_at' => '2020-01-01T00:00:00Z',
                        'pushed_at' => '2024-01-01T00:00:00Z',
                        'description' => 'The Laravel framework.',
                        'stargazers_count' => 1234,
                    ],
                    [
                        'id' => 202,
                        'full_name' => 'php/php-src',
                        'html_url' => 'https://github.com/php/php-src',
                        'created_at' => '2010-01-01T00:00:00Z',
                        'pushed_at' => '2024-01-02T00:00:00Z',
                        'description' => 'PHP source code.',
                        'stargazers_count' => 999,
                    ],
                ],
            ], 200),
        ]);

        $this->get('/github-projects');

        $this->assertDatabaseHas('github_php_projects', [
            'repoId' => 101,
            'name' => 'laravel/framework',
            'num_stars' => 1234,
        ]);
        $this->assertDatabaseHas('github_php_projects', [
            'repoId' => 202,
            'name' => 'php/php-src',
            'num_stars' => 999,
        ]);
    }
}
