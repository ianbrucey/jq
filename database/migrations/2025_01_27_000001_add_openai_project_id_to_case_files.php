<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_files', function (Blueprint $table) {
            $table->foreignId('openai_project_id')
                  ->nullable()
                  ->after('status')
                  ->constrained('openai_projects')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('case_files', function (Blueprint $table) {
            $table->dropForeign(['openai_project_id']);
            $table->dropColumn('openai_project_id');
        });
    }
};