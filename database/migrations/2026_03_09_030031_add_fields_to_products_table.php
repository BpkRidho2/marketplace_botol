<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_code')->nullable()->unique();
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('sizes')->nullable();
            $table->json('tags')->nullable();
            $table->decimal('discount_price', 15, 2)->nullable();
            $table->boolean('is_new')->default(false);
            $table->boolean('is_promo')->default(false);
            $table->boolean('is_best_seller')->default(false);
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'product_code',
                'weight',
                'sizes',
                'tags',
                'discount_price',
                'is_new',
                'is_promo',
                'is_best_seller',
                'short_description',
                'long_description',
            ]);
        });
    }
};
