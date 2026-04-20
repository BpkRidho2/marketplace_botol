<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->count() < 5) {
            User::factory(10)->create();
            $users = User::all();
        }

        foreach ($users as $user) {
            // Each user rates a few random products
            $count = min($products->count(), rand(2, 3));
            $randomProducts = $products->random($count);
            foreach ($randomProducts as $product) {
                Review::updateOrCreate(
                    ['user_id' => $user->id, 'product_id' => $product->id],
                    [
                        'rating' => rand(3, 5),
                        'comment' => 'Produk bagus dan berkualitas!'
                    ]
                );
            }
        }
    }
}
