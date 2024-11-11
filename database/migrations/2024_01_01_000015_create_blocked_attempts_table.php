<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocked_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->string('email')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('reason');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_attempts');
    }
};