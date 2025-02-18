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
        Schema::create('drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_file_id')->constrained('case_files')->cascadeOnDelete();
            $table->string('draft_type')->comment('complaint,motion,affidavit,letter,contract');
            $table->string('status')->default('draft')->comment('draft,review,published');
            $table->json('structured_context')->nullable();
            $table->text('ai_summary')->nullable();
            $table->timestamp('interview_completed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drafts');
    }
};
