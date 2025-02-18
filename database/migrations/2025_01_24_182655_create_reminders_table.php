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
      Schema::create('reminders', function (Blueprint $table) {
          $table->id();
          $table->foreignId('case_file_id')
                ->constrained()
                ->cascadeOnDelete();
          $table->string('title');
          $table->text('description')->nullable();
          $table->date('due_date');
          $table->timestamp('completed_at')->nullable();
          $table->boolean('is_recurring')->default(false);
          $table->string('recurrence_pattern')->nullable();
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
