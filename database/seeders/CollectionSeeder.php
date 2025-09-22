<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $collections = [
            [
                'name' => 'Summer Collection',
                'slug' => 'summer-collection',
                'description' => 'Fresh and trendy items for the summer season',
                'is_active' => true,
            ],
            [
                'name' => 'Winter Collection',
                'slug' => 'winter-collection',
                'description' => 'Warm and cozy items for the winter season',
                'is_active' => true,
            ],
            [
                'name' => 'New Arrivals',
                'slug' => 'new-arrivals',
                'description' => 'Latest products just added to our store',
                'is_active' => true,
            ],
            [
                'name' => 'Best Sellers',
                'slug' => 'best-sellers',
                'description' => 'Our most popular products chosen by customers',
                'is_active' => true,
            ],
            [
                'name' => 'Limited Edition',
                'slug' => 'limited-edition',
                'description' => 'Exclusive limited edition products',
                'is_active' => true,
            ],
        ];

        foreach ($collections as $collection) {
            Collection::create($collection);
        }
    }
}
