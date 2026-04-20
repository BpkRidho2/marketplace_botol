<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = \App\Models\User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        \App\Models\Shop::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'Toko Botol Jaya',
                'slug' => 'toko-botol-jaya',
                'description' => 'Toko perlengkapan botol dan jerigen plastik terlengkap.',
                'address' => 'Jl. Botol No. 123, Jakarta',
                'phone' => '08123456789'
            ]
        );

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
