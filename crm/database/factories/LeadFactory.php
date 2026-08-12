<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    public function definition(): array
    {
        $names = [
            'Andi Pratama', 'Budi Santoso', 'Citra Lestari', 'Dewi Anggraini',
            'Eko Wijaya', 'Fitri Handayani', 'Gunawan Saputra', 'Hendra Kurniawan',
            'Indah Permata', 'Joko Susilo', 'Kartika Dewi', 'Lukman Hakim',
            'Maya Sari', 'Nurul Aini', 'Bambang Setiawan', 'Rina Wulandari',
            'Agus Salim', 'Sri Rahayu', 'Fajar Nugroho', 'Ratna Sari',
        ];

        $companies = [
            'PT Sejahtera Abadi', 'CV Berkah Jaya', 'PT Mitra Niaga Sentosa',
            'UD Karya Mandiri', 'PT Sinar Utama', 'CV Makmur Sejahtera',
            'PT Bintang Timur', 'PT Garuda Persada', 'CV Karya Cipta',
            'PT Nusantara Perkasa', 'CV Harapan Bangsa', 'PT Citra Mandiri',
        ];

        $phones = [
            '0812-3456-7890', '0821-9876-5432', '0857-1234-5678', '0813-5555-1111',
            '0878-2222-3333', '0812-9999-8888', '0856-7777-6666', '0822-4444-5555',
        ];

        $contactName = $this->faker->randomElement($names);
        $companyName = $this->faker->randomElement($companies);
        $firstName = explode(' ', $contactName)[0];

        return [
            'user_id' => User::where('role', 'sales')->inRandomOrder()->first()?->id ?? User::factory(),
            'title' => 'Pengadaan layanan di '.$companyName,
            'company_name' => $companyName,
            'contact_name' => $contactName,
            'email' => strtolower(str_replace(' ', '', $firstName)).rand(1, 99).'@gmail.com',
            'phone' => $this->faker->randomElement($phones),
            'opportunity_value' => $this->faker->numberBetween(5_000_000, 150_000_000), // Rp 5jt - 150jt
            'status' => $this->faker->randomElement(['new', 'contacted', 'proposal', 'negotiation', 'won', 'lost']),
        ];
    }
}
