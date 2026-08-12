<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::where('role', 'sales')->inRandomOrder()->first()?->id ?? User::factory(),
            'lead_id' => null,
            'company_name' => $this->faker->company(),
            'contact_name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'status' => 'active',
            'total_lifetime_value' => $this->faker->numberBetween(10_000_000, 200_000_000),
            'notes' => $this->faker->sentence(),
        ];
    }
}