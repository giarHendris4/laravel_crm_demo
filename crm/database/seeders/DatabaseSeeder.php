<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Lead;
use App\Models\User;
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
        User::firstOrCreate(
            ['email' => 'admin@crm.com'], // Pencarian berdasarkan unique key
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Buat Akun Sales Representatives
        $sales1 = User::firstOrCreate(
            ['email' => 'sales1@crm.com'],
            [
                'name' => 'Budi Sales',
                'password' => Hash::make('password'),
                'role' => 'sales',
            ]
        );

        $sales2 = User::firstOrCreate(
            ['email' => 'sales2@crm.com'],
            [
                'name' => 'Siti Sales',
                'password' => Hash::make('password'),
                'role' => 'sales',
            ]
        );

        // 3. Buat Akun Partner Companies
        $partnerA = User::firstOrCreate(
            ['email' => 'partnera@crm.com'],
            [
                'name' => 'PT Mitra Finansial Utama',
                'password' => Hash::make('password'),
                'role' => 'partner',
            ]
        );

        $partnerB = User::firstOrCreate(
            ['email' => 'partnerb@crm.com'],
            [
                'name' => 'CV Solusi Properti',
                'password' => Hash::make('password'),
                'role' => 'partner',
            ]
        );

        // 4. Buat Master Data Kategori
        $cat1 = Category::firstOrCreate(
            ['name' => 'Asuransi Jiwa'],
            ['description' => 'Produk perlindungan jiwa dan kesehatan keluarga']
        );

        $cat2 = Category::firstOrCreate(
            ['name' => 'Kredit Kendaraan'],
            ['description' => 'Pembiayaan mobil baru dan bekas']
        );

        $cat3 = Category::firstOrCreate(
            ['name' => 'KPR Rumah'],
            ['description' => 'Kredit kepemilikan rumah dan properti']
        );

        $cat4 = Category::firstOrCreate(
            ['name' => 'Pinjaman Modal Usaha'],
            ['description' => 'Pembiayaan UMKM dan ekspansi bisnis']
        );

        // 5. Hubungkan Partner dengan Kategori
        // Gunakan syncWithoutDetaching agar aman dipanggil berkali-kali tanpa error duplicate entry di pivot table
        $partnerA->categories()->syncWithoutDetaching([$cat1->id, $cat2->id]);
        $partnerB->categories()->syncWithoutDetaching([$cat3->id, $cat4->id]);

        // 6. Generate Dummy Leads
        Lead::factory(15)->create();
    }
}
