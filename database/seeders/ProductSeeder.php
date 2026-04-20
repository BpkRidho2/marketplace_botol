<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $botol = Category::where('slug', 'pet')->first();
        $jerigen = Category::where('slug', 'hdpe')->first();
        $shop = Shop::first();

        Product::updateOrCreate(
            ['slug' => 'botol-pet-500ml'],
            [
                'category_id' => $botol->id,
                'shop_id' => $shop->id,
                'name' => 'Botol PET 500ml',
                'description' => 'Botol PET bening transparan ukuran 500ml kualitas premium.',
                'price' => 1500,
                'stock' => 1000,
                'material' => 'PET',
                'volume' => '500ml',
                'color' => 'Bening'
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'jerigen-hdpe-5l'],
            [
                'category_id' => $jerigen->id,
                'shop_id' => $shop->id,
                'name' => 'Jerigen HDPE 5L',
                'description' => 'Jerigen plastik HDPE tebal ukuran 5 liter warna putih susu.',
                'price' => 8500,
                'stock' => 500,
                'material' => 'HDPE',
                'volume' => '5L',
                'color' => 'Putih Susu'
            ]
        );

        Product::updateOrCreate(
            ['slug' => 'jerigen-hdpe-20l-blue'],
            [
                'category_id' => $jerigen->id,
                'shop_id' => $shop->id,
                'name' => 'Jerigen HDPE 20L Blue',
                'description' => 'Jerigen plastik HDPE industri 20 liter warna biru kuat.',
                'price' => 25000,
                'stock' => 200,
                'material' => 'HDPE',
                'volume' => '20L',
                'color' => 'Biru'
            ]
        );
    }
}
