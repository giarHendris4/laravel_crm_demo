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
        Schema::table('leads', function (Blueprint $table) {
            // Menambahkan foreign key lead_category_id setelah kolom user_id
            $table->foreignId('lead_category_id')
                ->nullable()
                ->after('user_id')
                ->constrained('lead_categories')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Hapus foreign key dan kolomnya jika migration di-rollback
            $table->dropForeign(['lead_category_id']);
            $table->dropColumn('lead_category_id');
        });
    }
};
