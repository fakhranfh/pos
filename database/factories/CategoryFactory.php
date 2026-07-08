<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Minuman',
                'Makanan Ringan',
                'Makanan Instan',
                'Bumbu Dapur',
                'Perlengkapan Mandi',
                'Alat Tulis',
                'Rokok',
                'Kebutuhan Bayi',
            ]),
        ];
    }
}
