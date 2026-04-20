<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::updateOrCreate(
            ['slug' => 'pet'],
            ['name' => 'PET', 'description' => 'Botol PET berkualitas premium untuk berbagai kebutuhan.']
        );
        \App\Models\Category::updateOrCreate(
            ['slug' => 'hdpe'],
            ['name' => 'HDPE', 'description' => 'Jerigen HDPE tahan lama untuk produk industri.']
        );
        \App\Models\Category::updateOrCreate(
            ['slug' => 'pet-toples'],
            ['name' => 'PET TOPLES', 'description' => 'Wadah PET berbentuk toples untuk sektor ritel.']
        );
        \App\Models\Category::updateOrCreate(
            ['slug' => 'galon'],
            ['name' => 'GALON', 'description' => 'Galon plastik besar untuk kemasan massal.']
        );
        \App\Models\Category::updateOrCreate(
            ['slug' => 'custom-order'],
            ['name' => 'Custom Order', 'description' => 'Kemasan custom sesuai spesifikasi Anda.']
        );
    }
}
