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
        Schema::create('docket_deadlines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docket_entry_id')->constrained()->cascadeOnDelete();
            $table->date('deadline_date');
            $table->string('deadline_type');
            $table->text('description')->nullable();
            $table->foreignId('reminder_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docket_deadlines');
    }
};
