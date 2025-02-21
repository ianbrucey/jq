<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_token_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_access_token_id')->constrained('personal_access_tokens')->cascadeOnDelete();
            $table->string('endpoint');
            $table->string('method');
            $table->string('ip_address');
            $table->integer('response_code');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_token_usage');
    }
};