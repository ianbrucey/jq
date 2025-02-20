<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('case_files', function (Blueprint $table) {
            $table->string('openai_assistant_id')->nullable()->after('status');
            $table->string('openai_vector_store_id')->nullable()->after('openai_assistant_id');
        });
    }

    public function down(): void
    {
        Schema::table('case_files', function (Blueprint $table) {
            $table->dropColumn(['openai_assistant_id', 'openai_vector_store_id']);
        });
    }
};