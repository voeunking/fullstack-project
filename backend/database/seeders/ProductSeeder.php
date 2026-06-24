<?php

namespace Database\Seeders;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Computer' => [
                ['name' => 'MacBook Pro 16"', 'price' => 2499, 'description' => 'Apple M3 Pro chip with 18GB RAM and 512GB SSD'],
                ['name' => 'Dell XPS 15', 'price' => 1799, 'description' => '15.6" 4K display, Intel i7, 32GB RAM, 1TB SSD'],
                ['name' => 'Gaming Desktop', 'price' => 1899, 'description' => 'RTX 4080, AMD Ryzen 9, 64GB RAM, 2TB NVMe'],
                ['name' => 'Wireless Mouse', 'price' => 79, 'description' => 'Ergonomic design with 2-year battery life'],
            ],
            'Phones' => [
                ['name' => 'iPhone 15 Pro', 'price' => 999, 'description' => '6.1" display, A17 Pro chip, 128GB storage'],
                ['name' => 'Samsung Galaxy S24', 'price' => 899, 'description' => '6.2" Dynamic AMOLED, Snapdragon 8 Gen 3, 256GB'],
                ['name' => 'Google Pixel 8', 'price' => 699, 'description' => '6.2" OLED, Tensor G3, 128GB storage'],
                ['name' => 'Phone Case Pro', 'price' => 29, 'description' => 'Military-grade protection with wireless charging'],
            ],
            'Audio' => [
                ['name' => 'AirPods Pro', 'price' => 249, 'description' => 'Active noise cancellation, spatial audio'],
                ['name' => 'Sony WH-1000XM5', 'price' => 399, 'description' => 'Industry-leading noise canceling headphones'],
                ['name' => 'JBL Flip 6', 'price' => 129, 'description' => 'Waterproof portable Bluetooth speaker'],
            ],
            'Wearables' => [
                ['name' => 'Apple Watch Series 9', 'price' => 429, 'description' => 'GPS, heart rate monitor, fitness tracking'],
                ['name' => 'Samsung Galaxy Watch6', 'price' => 299, 'description' => 'AMOLED display, health monitoring'],
            ],
            'Accessories' => [
                ['name' => 'USB-C Hub', 'price' => 49, 'description' => '7-in-1 multi-port adapter for laptops'],
                ['name' => 'Laptop Stand', 'price' => 39, 'description' => 'Adjustable aluminum stand for MacBook'],
                ['name' => 'Cable Pack', 'price' => 25, 'description' => 'USB-C, Lightning, and HDMI cables (3-pack)'],
            ],
        ];

        foreach ($categories as $categoryName => $products) {
            $category = Category::where('name', $categoryName)->first();
            if ($category) {
                foreach ($products as $product) {
                    Product::create([
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'description' => $product['description'],
                        'category_id' => $category->id,
                        'image' => null,
                    ]);
                }
            }
        }
    }
}