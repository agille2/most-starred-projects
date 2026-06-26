<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('github_php_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repoId')->unique();
            $table->string('name');
            $table->string('url');
            $table->timestamp('created_date')->nullable();
            $table->timestamp('last_push_date')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('num_stars')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('github_php_projects');
    }
};
