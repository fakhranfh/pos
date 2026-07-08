<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            'Minuman',
            'Makanan Ringan',
            'Makanan Instan',
            'Bumbu Dapur',
            'Perlengkapan Mandi',
            'Alat Tulis',
            'Rokok',
            'Kebutuhan Bayi',
        ])->each(fn (string $name) => Category::firstOrCreate(['name' => $name]));
    }
}
