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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_file_id')->constrained()->cascadeOnDelete();
            $table->string('storage_path');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->bigInteger('file_size');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('openai_file_id')->nullable();
            $table->enum('ingestion_status', ['pending', 'uploading', 'summarizing', 'indexing', 'indexed', 'failed'])
                ->default('pending');
            $table->text('ingestion_error')->nullable();
            $table->timestamp('ingested_at')->nullable();
            $table->boolean('skip_vector_store')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
