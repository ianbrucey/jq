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
        Schema::create('case_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_file_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['viewer', 'editor', 'manager']);
            $table->enum('status', ['pending', 'active', 'revoked'])->default('pending');
            $table->timestamps();

            $table->unique(['case_file_id', 'user_id']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_collaborators');
    }
};
