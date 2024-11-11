<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Categories tablosu için indexler
        Schema::table('categories', function (Blueprint $table) {
            $table->index('status');
            $table->index('show_in_menu');
            $table->index('use_as_filter');
            $table->index(['status', 'show_in_menu']);
            $table->index('daily_rate');
        });

        // Rentals tablosu için indexler
        Schema::table('rentals', function (Blueprint $table) {
            $table->index('status');
            $table->index('start_date');
            $table->index('end_date');
            $table->index(['status', 'start_date', 'end_date']);
            $table->index('total_amount');
        });

        // Posts tablosu için indexler
        Schema::table('posts', function (Blueprint $table) {
            $table->index('status');
            $table->index(['category_id', 'status']);
            $table->index('created_at');
        });

        // Pages tablosu için indexler
        Schema::table('pages', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
        });

        // Users tablosu için indexler
        Schema::table('users', function (Blueprint $table) {
            $table->index('status');
            $table->index('email_verified_at');
            $table->index('created_at');
        });

        // Visitors tablosu için indexler
        Schema::table('visitors', function (Blueprint $table) {
            $table->index('ip_address');
            $table->index('created_at');
            $table->index(['ip_address', 'created_at']);
        });

        // Security_logs tablosu için indexler
        Schema::table('security_logs', function (Blueprint $table) {
            $table->index('ip_address');
            $table->index(['event_type', 'severity']);
            $table->index(['user_id', 'event_type']);
        });

        // User_statistics tablosu için indexler
        Schema::table('user_statistics', function (Blueprint $table) {
            $table->index(['user_id', 'action_type']);
            $table->index('ip_address');
            $table->index('device_type');
            $table->index('created_at');
        });

        // Payments tablosu için indexler
        Schema::table('payments', function (Blueprint $table) {
            $table->index('status');
            $table->index('payment_method');
            $table->index('transaction_id');
            $table->index(['rental_id', 'status']);
        });

        // Invoices tablosu için indexler
        Schema::table('invoices', function (Blueprint $table) {
            $table->index('invoice_number');
            $table->index(['rental_id', 'created_at']);
        });

        // Tags tablosu için indexler
        Schema::table('tags', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        // Categories indexlerini kaldır
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['show_in_menu']);
            $table->dropIndex(['use_as_filter']);
            $table->dropIndex(['status', 'show_in_menu']);
            $table->dropIndex(['daily_rate']);
        });

        // Rentals indexlerini kaldır
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['start_date']);
            $table->dropIndex(['end_date']);
            $table->dropIndex(['status', 'start_date', 'end_date']);
            $table->dropIndex(['total_amount']);
        });

        // Posts indexlerini kaldır
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['category_id', 'status']);
            $table->dropIndex(['created_at']);
        });

        // Pages indexlerini kaldır
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });

        // Users indexlerini kaldır
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['email_verified_at']);
            $table->dropIndex(['created_at']);
        });

        // Visitors indexlerini kaldır
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropIndex(['ip_address']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['ip_address', 'created_at']);
        });

        // Security_logs indexlerini kaldır
        Schema::table('security_logs', function (Blueprint $table) {
            $table->dropIndex(['ip_address']);
            $table->dropIndex(['event_type', 'severity']);
            $table->dropIndex(['user_id', 'event_type']);
        });

        // User_statistics indexlerini kaldır
        Schema::table('user_statistics', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'action_type']);
            $table->dropIndex(['ip_address']);
            $table->dropIndex(['device_type']);
            $table->dropIndex(['created_at']);
        });

        // Payments indexlerini kaldır
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_method']);
            $table->dropIndex(['transaction_id']);
            $table->dropIndex(['rental_id', 'status']);
        });

        // Invoices indexlerini kaldır
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['invoice_number']);
            $table->dropIndex(['rental_id', 'created_at']);
        });

        // Tags indexlerini kaldır
        Schema::table('tags', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });
    }
};