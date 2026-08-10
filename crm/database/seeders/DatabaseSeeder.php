<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@crm.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Buat Akun Sales Representatives
        $sales1 = User::create([
            'name' => 'Budi Sales',
            'email' => 'sales1@crm.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
        ]);

        $sales2 = User::create([
            'name' => 'Siti Sales',
            'email' => 'sales2@crm.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
        ]);

        // 3. Buat Akun Partner Companies
        $partnerA = User::create([
            'name' => 'PT Mitra Finansial Utama',
            'email' => 'partnera@crm.com',
            'password' => Hash::make('password'),
            'role' => 'partner',
        ]);

        $partnerB = User::create([
            'name' => 'CV Solusi Properti',
            'email' => 'partnerb@crm.com',
            'password' => Hash::make('password'),
            'role' => 'partner',
        ]);

        // 4. Buat Master Data Kategori
        $cat1 = Category::create([
            'name' => 'Asuransi Jiwa',
            'description' => 'Produk perlindungan jiwa dan kesehatan keluarga',
        ]);

        $cat2 = Category::create([
            'name' => 'Kredit Kendaraan',
            'description' => 'Pembiayaan mobil baru dan bekas',
        ]);

        $cat3 = Category::create([
            'name' => 'KPR Rumah',
            'description' => 'Kredit kepemilikan rumah dan properti',
        ]);

        $cat4 = Category::create([
            'name' => 'Pinjaman Modal Usaha',
            'description' => 'Pembiayaan UMKM dan ekspansi bisnis',
        ]);

        // 5. Hubungkan Partner dengan Kategori (Pivot Table: partner_categories)
        // Partner A menangani Asuransi Jiwa & Kredit Kendaraan
        $partnerA->categories()->attach([$cat1->id, $cat2->id]);

        // Partner B menangani KPR Rumah & Pinjaman Modal
        $partnerB->categories()->attach([$cat3->id, $cat4->id]);
    }
}