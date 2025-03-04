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
        Schema::create('communication_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('communication_id')->constrained()->cascadeOnDelete();
            $table->foreignId('party_id')->constrained('parties');
            $table->enum('role', ['sender', 'recipient']);
            $table->timestamps();

            $table->unique(['communication_id', 'party_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communication_participants');
    }
};
