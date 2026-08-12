<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah index untuk query yang sering dipakai agar tidak full table scan
     * pada data dalam jumlah besar.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->index('stage');
            $table->index('created_at');
            $table->index(['user_id', 'stage']);
            $table->index(['user_id', 'created_at']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });

        Schema::table('lead_assignments', function (Blueprint $table) {
            $table->index(['partner_id', 'lead_id']);
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status']);
        });

        Schema::table('deals', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['user_id', 'stage']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['stage']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'created_at']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status']);
        });

        Schema::table('lead_assignments', function (Blueprint $table) {
            $table->dropIndex(['partner_id', 'lead_id']);
        });
    }
};
