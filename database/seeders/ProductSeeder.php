<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productsByCategory = [
            'Minuman' => [
                ['name' => 'Aqua Botol 600ml', 'price' => 4000, 'cost_price' => 3000],
                ['name' => 'Teh Botol Sosro 450ml', 'price' => 5500, 'cost_price' => 4200],
                ['name' => 'Coca-Cola Kaleng 330ml', 'price' => 7000, 'cost_price' => 5500],
                ['name' => 'Kopi Kapal Api Sachet', 'price' => 1500, 'cost_price' => 1000],
                ['name' => 'Pocari Sweat 500ml', 'price' => 9000, 'cost_price' => 7200],
                ['name' => 'Ultra Milk Coklat 250ml', 'price' => 6000, 'cost_price' => 4800],
            ],
            'Makanan Ringan' => [
                ['name' => 'Chitato Sapi Panggang', 'price' => 11000, 'cost_price' => 9000],
                ['name' => 'Taro Net Snack', 'price' => 8500, 'cost_price' => 6800],
                ['name' => 'Oreo Original', 'price' => 9500, 'cost_price' => 7500],
                ['name' => 'Better Biskuit Coklat', 'price' => 7000, 'cost_price' => 5500],
                ['name' => 'SilverQueen Chunky Bar', 'price' => 15000, 'cost_price' => 12000],
                ['name' => 'Qtela Singkong Balado', 'price' => 9000, 'cost_price' => 7200],
            ],
            'Makanan Instan' => [
                ['name' => 'Indomie Goreng', 'price' => 3000, 'cost_price' => 2500],
                ['name' => 'Mie Sedaap Soto', 'price' => 3000, 'cost_price' => 2500],
                ['name' => 'Pop Mie Ayam', 'price' => 5000, 'cost_price' => 4000],
                ['name' => 'Sarimi Isi 2 Ayam Bawang', 'price' => 3500, 'cost_price' => 2800],
                ['name' => 'ABC Mie Kari Ayam', 'price' => 3200, 'cost_price' => 2600],
            ],
            'Bumbu Dapur' => [
                ['name' => 'Royco Kaldu Ayam', 'price' => 2000, 'cost_price' => 1500],
                ['name' => 'Bango Kecap Manis 220ml', 'price' => 12000, 'cost_price' => 9500],
                ['name' => 'Indofood Saus Sambal 340ml', 'price' => 13000, 'cost_price' => 10500],
                ['name' => 'Masako Rasa Sapi', 'price' => 2500, 'cost_price' => 2000],
                ['name' => 'Garam Dolphin 250gr', 'price' => 3000, 'cost_price' => 2200],
            ],
            'Perlengkapan Mandi' => [
                ['name' => 'Lifebuoy Sabun Mandi', 'price' => 4500, 'cost_price' => 3500],
                ['name' => 'Pepsodent Pasta Gigi 190gr', 'price' => 12000, 'cost_price' => 9500],
                ['name' => 'Sunsilk Shampoo Sachet', 'price' => 1000, 'cost_price' => 700],
                ['name' => 'Nuvo Family Sabun Batang', 'price' => 4000, 'cost_price' => 3200],
                ['name' => 'Rexona Deodorant Roll On', 'price' => 18000, 'cost_price' => 14500],
            ],
            'Alat Tulis' => [
                ['name' => 'Pulpen Standard AE7', 'price' => 2500, 'cost_price' => 1800],
                ['name' => 'Buku Tulis Sinar Dunia', 'price' => 3500, 'cost_price' => 2800],
                ['name' => 'Pensil Faber Castell 2B', 'price' => 3000, 'cost_price' => 2200],
                ['name' => 'Penghapus Joyko', 'price' => 1500, 'cost_price' => 1000],
                ['name' => 'Penggaris Butterfly 30cm', 'price' => 3000, 'cost_price' => 2000],
            ],
            'Rokok' => [
                ['name' => 'Gudang Garam Filter', 'price' => 24000, 'cost_price' => 21500],
                ['name' => 'Sampoerna Mild', 'price' => 27000, 'cost_price' => 24000],
                ['name' => 'Djarum Super', 'price' => 25000, 'cost_price' => 22000],
                ['name' => 'Marlboro Merah', 'price' => 32000, 'cost_price' => 28500],
            ],
            'Kebutuhan Bayi' => [
                ['name' => 'Pampers Popok Bayi M isi 20', 'price' => 45000, 'cost_price' => 38000],
                ['name' => 'SGM Susu Formula 400gr', 'price' => 55000, 'cost_price' => 47000],
                ['name' => 'Zwitsal Baby Oil 100ml', 'price' => 18000, 'cost_price' => 14500],
                ['name' => 'Johnson\'s Bedak Bayi 100gr', 'price' => 15000, 'cost_price' => 12000],
                ['name' => 'Tisu Basah Mitu', 'price' => 8000, 'cost_price' => 6500],
            ],
        ];

        $skuPrefixes = [
            'Minuman' => 'MIN',
            'Makanan Ringan' => 'SNK',
            'Makanan Instan' => 'INS',
            'Bumbu Dapur' => 'BMB',
            'Perlengkapan Mandi' => 'MND',
            'Alat Tulis' => 'ATK',
            'Rokok' => 'ROK',
            'Kebutuhan Bayi' => 'BAY',
        ];

        foreach ($productsByCategory as $categoryName => $products) {
            $category = Category::where('name', $categoryName)->first();

            foreach ($products as $index => $product) {
                $sku = $skuPrefixes[$categoryName].'-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);

                Product::create([
                    'category_id' => $category->id,
                    'sku' => $sku,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'cost_price' => $product['cost_price'],
                    'stock' => fake()->numberBetween(5, 120),
                    'low_stock_threshold' => fake()->numberBetween(5, 15),
                    'image_url' => $this->uploadPlaceholderImage($sku, $product['name']),
                    'is_active' => true,
                ]);
            }
        }
    }

    /**
     * Generate a simple SVG placeholder image for a product and upload it to
     * the public disk, returning the stored path.
     */
    private function uploadPlaceholderImage(string $sku, string $name): string
    {
        $colors = ['#2563eb', '#059669', '#d97706', '#dc2626', '#7c3aed', '#0891b2'];
        $color = $colors[crc32($sku) % count($colors)];
        $initials = strtoupper(collect(explode(' ', $name))->take(2)->map(fn ($word) => $word[0] ?? '')->implode(''));

        $svg = <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" width="300" height="300">
            <rect width="300" height="300" fill="{$color}" />
            <text x="50%" y="50%" font-family="sans-serif" font-size="80" fill="#ffffff" text-anchor="middle" dominant-baseline="middle">{$initials}</text>
        </svg>
        SVG;

        $path = "products/{$sku}.svg";

        Storage::disk('public')->put($path, $svg);

        return $path;
    }
}
