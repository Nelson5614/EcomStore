<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'title' => 'Apple iPhone 13 Pro',
            'slug' => 'apple-iphone-13-pro',
            'description' => 'Apple iPhone 13 Pro is a high-end smartphone designed by Apple. It features a 5.8-inch Super Retina display, a 128GB/512GB storage, and a quad-camera system. It also has a powerful A14 Bionic chip, making it one of the most powerful smartphones on the market.',
            'price' => 999,
            'quantity' => 10,
            'is_published' => 1,
            'instock' => 10,
            'brand_id' => 1,
            'category_id' => 1,
        ]);

        Product::create([
            'title' => 'Samsung Galaxy S21',
            'slug' => 'samsung-galaxy-s21',
            'description' => 'Samsung Galaxy S21 is a high-end smartphone designed by Samsung. It features a 6.2-inch Super AMOLED display, a 128GB/512GB storage, and a quad-camera system. It also has a powerful Exynos chip, making it one of the most powerful smartphones on the market.',
            'price' => 999,
            'quantity' => 10,
            'is_published' => 1,
            'instock' => 10,
            'brand_id' => 2,
            'category_id' => 1,
        ]);

        Product::create([
            'title' => 'Huawei P30 Pro',
            'slug' => 'huawei-p30-pro',
            'description' => 'Huawei P30 Pro is a high-end smartphone designed by Huawei. It features a 6.2-inch Super AMOLED display, a 128GB/512GB storage, and a quad-camera system. It also has a powerful Exynos chip, making it one of the most powerful smartphones on the market.',
            'price' => 999,
            'quantity' => 10,
            'is_published' => 1,
            'instock' => 10,
            'brand_id' => 3,
            'category_id' => 1,
        ]);
        Product::create([
            'title' => 'Apple iPad Air',
            'slug' => 'apple-ipad-air',
            'description' => 'Apple iPad Air is a high-end tablet designed by Apple. It features a 10.9-inch Super Retina display, a 128GB/512GB storage, and a quad-camera system. It also has a powerful A14 Bionic chip, making it one of the most powerful tablets on the market.',
            'price' => 999,
            'quantity' => 10,
            'is_published' => 1,
            'instock' => 10,
            'brand_id' => 1,
            'category_id' => 2,
        ]);

        Product::create([
            'title' => 'Samsung Galaxy Tab S7',
            'slug' => 'samsung-galaxy-tab-s7',
            'description' => 'Samsung Galaxy Tab S7 is a high-end tablet designed by Samsung. It features a 10.1-inch Super AMOLED display, a 128GB/512GB storage, and a quad-camera system. It also has a powerful Exynos chip, making it one of the most powerful tablets on the market.',
            'price' => 999,
            'quantity' => 10,
            'is_published' => 1,
            'instock' => 10,
            'brand_id' => 2,
            'category_id' => 2,
        ]);

        Product::create([
            'title' => 'Huawei Mate 30',
            'slug' => 'huawei-mate-30',
            'description' => 'Huawei Mate 30 is a high-end tablet designed by Huawei. It features a 10.1-inch Super AMOLED display, a 128GB/512GB storage, and a quad-camera system. It also has a powerful Exynos chip, making it one of the most powerful tablets on the market.',
            'price' => 999,
            'quantity' => 10,
            'is_published' => 1,
            'instock' => 10,
            'brand_id' => 3,
            'category_id' => 2,
        ]);
    }
}
