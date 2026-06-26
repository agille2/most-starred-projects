<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GitHubProject extends Model
{
    protected $table = 'github_php_projects';

    protected $fillable = [
        'repoId',
        'name',
        'url',
        'created_date',
        'last_push_date',
        'description',
        'num_stars',
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'last_push_date' => 'datetime',
        'num_stars' => 'integer',
    ];
}
