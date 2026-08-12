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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Sales (User) yang menangani akun customer ini
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Tracing asal-usul lead (opsional, set null jika lead dihapus)
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            
            // Profil Utama
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            // Informasi Bisnis/Status Customer
            $table->enum('status', ['active', 'inactive', 'churned'])->default('active');
            $table->decimal('total_lifetime_value', 15, 2)->default(0); // Total nilai transaksi sejauh ini
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};