<?php

use App\Http\Controllers\GitHubProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GitHubProjectController::class, 'index'])->name('github.projects');

Route::get('/github-projects', [GitHubProjectController::class, 'index']);
Route::post('/github-projects/refresh', [GitHubProjectController::class, 'refresh'])->name('github.projects.refresh');
Route::get('/github-projects/{project}', [GitHubProjectController::class, 'show'])->name('github.projects.show');
