<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah tipe deal_value menjadi decimal agar konsisten
     * dengan leads.opportunity_value (decimal(15,2)).
     */
    public function up(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->decimal('deal_value', 15, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('deals', function (Blueprint $table) {
            $table->bigInteger('deal_value')->default(0)->change();
        });
    }
};
