<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run(): void {
    $products = [
      [
        'name' => 'Laptop Legion',
        'description' => 'Laptop gaming ini bos',
        'price' => 27000000,
        'stock' => 10,
      ],
      [
        'name' => 'Smartphone Galaxy',
        'description' => 'Smartphone canggih dengan kamera terbaik',
        'price' => 15000000,
        'stock' => 20,
      ],
      [
        'name' => 'Headphones Bose',
        'description' => 'Headphones dengan kualitas suara premium',
        'price' => 3000000,
        'stock' => 15,
      ],
      [
        'name' => 'Smartwatch Apple',
        'description' => 'Smartwatch dengan fitur kesehatan lengkap',
        'price' => 5000000,
        'stock' => 8,
      ],
      [
        'name' => 'Tablet Samsung',
        'description' => 'Tablet dengan layar besar dan performa tinggi',
        'price' => 4000000,
        'stock' => 12,
      ],
    ];

    foreach ($products as $product) {
      Product::create($product);
    }
  }
}
