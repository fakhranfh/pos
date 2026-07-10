<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['name' => 'Andi Prasetyo', 'email' => 'andi.admin@possystem.test', 'role' => UserRole::Admin],
            ['name' => 'Budi Santoso', 'email' => 'budi.kasir@possystem.test', 'role' => UserRole::Cashier],
            ['name' => 'Siti Rahayu', 'email' => 'siti.kasir@possystem.test', 'role' => UserRole::Cashier],
        ])->each(fn (array $user) => User::factory()->create($user));
    }
}
