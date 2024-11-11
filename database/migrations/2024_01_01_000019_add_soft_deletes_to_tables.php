<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategoriler için soft delete
        Schema::table('categories', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Kiralamalar için soft delete
        Schema::table('rentals', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Blog yazıları için soft delete
        Schema::table('posts', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Sayfalar için soft delete
        Schema::table('pages', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Kullanıcılar için soft delete
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('rentals', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};