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
        Schema::create('docket_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_file_id')->constrained()->cascadeOnDelete();
            $table->date('entry_date');
            $table->enum('entry_type', ['filing', 'order', 'hearing', 'notice', 'motion', 'judgment', 'other']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('filing_party')->nullable();
            $table->string('judge')->nullable();
            $table->string('docket_number')->nullable();
            $table->enum('status', ['pending', 'granted', 'denied', 'heard', 'continued', 'withdrawn'])->nullable();
            $table->boolean('is_sealed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docket_entries');
    }
};
