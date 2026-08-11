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
    Schema::create('leads', function (Blueprint $table) {
        $table->id();
        
        // Relasi ke Sales (User yang menangani)
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        
        // Data Kontak / Perusahaan
        $table->string('title'); // Contoh: "Pengadaan Laptop Kantor PT ABC"
        $table->string('company_name');
        $table->string('contact_name');
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        
        // Nilai Potensi Transaksi
        $table->decimal('opportunity_value', 15, 2)->default(0); // Nilai Rupiah
        
        // Status Prospek
        $table->enum('status', [
            'new',          // Baru masuk
            'contacted',    // Sudah dihubungi
            'proposal',     // Penawaran dikirim
            'negotiation',  // Tahap negosiasi
            'won',          // Berhasil / Deal
            'lost'          // Gagal / Batal
        ])->default('new');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
