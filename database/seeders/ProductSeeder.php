<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Laptop',
                'description' => 'High-performance laptop for professionals',
                'price' => 999.99,
                'stock' => 10,
                'image_url' => '/images/laptop.jpg'
            ],
            [
                'name' => 'Smartphone',
                'description' => 'Latest smartphone with advanced features',
                'price' => 699.99,
                'stock' => 15,
                'image_url' => '/images/smartphone.jpg'
            ],
            // Add more products as needed
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
