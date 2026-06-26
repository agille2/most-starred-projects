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
        Schema::table('github_php_projects', function (Blueprint $table) {
            $table->unsignedBigInteger('repoId')->nullable(false)->change();
        });

        $schemaBuilder = Schema::getConnection()->getSchemaBuilder();

        if (! $schemaBuilder->hasIndex('github_php_projects', ['repoId'])) {
            Schema::table('github_php_projects', function (Blueprint $table) {
                $table->unique('repoId');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $schemaBuilder = Schema::getConnection()->getSchemaBuilder();

        if ($schemaBuilder->hasIndex('github_php_projects', ['repoId'])) {
            Schema::table('github_php_projects', function (Blueprint $table) {
                $table->dropUnique(['repoId']);
            });
        }

        Schema::table('github_php_projects', function (Blueprint $table) {
            $table->unsignedBigInteger('repoId')->nullable()->change();
        });
    }
};
