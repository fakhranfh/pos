<?php

namespace Database\Seeders;

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
            ['name' => 'Andi Prasetyo', 'email' => 'andi.admin@possystem.test'],
            ['name' => 'Budi Santoso', 'email' => 'budi.kasir@possystem.test'],
            ['name' => 'Siti Rahayu', 'email' => 'siti.kasir@possystem.test'],
        ])->each(fn (array $user) => User::factory()->create($user));
    }
}
