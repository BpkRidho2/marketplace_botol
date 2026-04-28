<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        Shop::updateOrCreate(
            ['slug' => 'toko-botol-jaya'],
            [
                'user_id' => $user->id,
                'name' => 'Toko Botol Jaya',
                'slug' => 'toko-botol-jaya',
                'description' => 'Toko perlengkapan botol dan jerigen plastik terlengkap.',
                'address' => 'Jl. Botol No. 123, Jakarta',
                'phone' => '08123456789'
            ]
        );

        User::factory()->count(25)->create();

$this->call([
    AdminSeeder::class,   // Akun admin pertama
    CategorySeeder::class,
    ProductSeeder::class,
    ReviewSeeder::class,
]);
    }
}
