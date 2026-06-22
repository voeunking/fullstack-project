<?php

namespace Database\Seeders;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronics = Category::where('name', 'Computer')->first();

         Product::create([
            'name' => 'Computer',
            'price' => 999,
            'description' => 'Apple smartphone',
            'category_id' => $electronics->id,
            'image' => 'products/iphone.jpg'
        ]);
    }
}
