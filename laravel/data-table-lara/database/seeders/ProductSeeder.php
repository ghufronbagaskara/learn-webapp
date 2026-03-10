<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run(): void {
    $categories = ['Electronics', 'Fashion', 'Food & Beverage', 'Sports', 'Home & Living', 'Beauty', 'Automotive', 'Books'];
    $statuses = ['active', 'active', 'active', 'inactive', 'draft'];

    $products = [
      ['name' => 'iPhone 15 Pro Max', 'category' => 'Electronics', 'price' => 22999000, 'stock' => 45],
      ['name' => 'Samsung Galaxy S24 Ultra', 'category' => 'Electronics', 'price' => 19999000, 'stock' => 30],
      ['name' => 'MacBook Pro M3', 'category' => 'Electronics', 'price' => 35999000, 'stock' => 12],
      ['name' => 'Sony WH-1000XM5', 'category' => 'Electronics', 'price' => 4999000, 'stock' => 80],
      ['name' => 'iPad Air M2', 'category' => 'Electronics', 'price' => 12999000, 'stock' => 25],
      ['name' => 'Nike Air Max 270', 'category' => 'Fashion', 'price' => 1899000, 'stock' => 150],
      ['name' => 'Adidas Ultraboost 22', 'category' => 'Fashion', 'price' => 2499000, 'stock' => 90],
      ['name' => 'Levi\'s 501 Original', 'category' => 'Fashion', 'price' => 899000, 'stock' => 200],
      ['name' => 'Uniqlo Heattech Inner', 'category' => 'Fashion', 'price' => 299000, 'stock' => 500],
      ['name' => 'New Balance 574', 'category' => 'Fashion', 'price' => 1599000, 'stock' => 75],
      ['name' => 'Nescafe Gold 200g', 'category' => 'Food & Beverage', 'price' => 89000, 'stock' => 1000],
      ['name' => 'Milo Activ-Go 1kg', 'category' => 'Food & Beverage', 'price' => 75000, 'stock' => 850],
      ['name' => 'Teh Botol Sosro 330ml', 'category' => 'Food & Beverage', 'price' => 8000, 'stock' => 2400],
      ['name' => 'Indomie Goreng Rendang', 'category' => 'Food & Beverage', 'price' => 4500, 'stock' => 5000],
      ['name' => 'Aqua 1500ml', 'category' => 'Food & Beverage', 'price' => 7000, 'stock' => 3000],
      ['name' => 'Badminton Racket Yonex', 'category' => 'Sports', 'price' => 1299000, 'stock' => 40],
      ['name' => 'Sepeda MTB Polygon', 'category' => 'Sports', 'price' => 4999000, 'stock' => 15],
      ['name' => 'Dumbell Set 10kg', 'category' => 'Sports', 'price' => 599000, 'stock' => 60],
      ['name' => 'Yoga Mat Premium', 'category' => 'Sports', 'price' => 249000, 'stock' => 120],
      ['name' => 'Treadmill Elektrik', 'category' => 'Sports', 'price' => 8999000, 'stock' => 8],
      ['name' => 'Kursi Gaming ErgoMax', 'category' => 'Home & Living', 'price' => 3499000, 'stock' => 20],
      ['name' => 'Lampu LED Philips 12W', 'category' => 'Home & Living', 'price' => 45000, 'stock' => 2000],
      ['name' => 'Dispenser Cosmos Hot&Cool', 'category' => 'Home & Living', 'price' => 599000, 'stock' => 35],
      ['name' => 'Rak Buku Minimalis', 'category' => 'Home & Living', 'price' => 399000, 'stock' => 50],
      ['name' => 'Kasur Spring Bed King', 'category' => 'Home & Living', 'price' => 7999000, 'stock' => 10],
      ['name' => 'Wardah Sunscreen SPF50', 'category' => 'Beauty', 'price' => 79000, 'stock' => 800],
      ['name' => 'Skintific Moisturizer', 'category' => 'Beauty', 'price' => 159000, 'stock' => 450],
      ['name' => 'Emina Bright Stuff', 'category' => 'Beauty', 'price' => 55000, 'stock' => 600],
      ['name' => 'Garnier Micellar Water', 'category' => 'Beauty', 'price' => 69000, 'stock' => 700],
      ['name' => 'Scarlett Body Lotion', 'category' => 'Beauty', 'price' => 99000, 'stock' => 550],
      ['name' => 'Ban Motor IRC 80/90', 'category' => 'Automotive', 'price' => 199000, 'stock' => 300],
      ['name' => 'Aki Motor GS 12V', 'category' => 'Automotive', 'price' => 350000, 'stock' => 120],
      ['name' => 'Oli Mesin Pertamina Fastron', 'category' => 'Automotive', 'price' => 85000, 'stock' => 900],
      ['name' => 'Cover Motor Tebal', 'category' => 'Automotive', 'price' => 125000, 'stock' => 200],
      ['name' => 'Helm Full Face INK', 'category' => 'Automotive', 'price' => 499000, 'stock' => 85],
      ['name' => 'Clean Code by Robert Martin', 'category' => 'Books', 'price' => 189000, 'stock' => 60],
      ['name' => 'Laravel Up & Running', 'category' => 'Books', 'price' => 245000, 'stock' => 40],
      ['name' => 'Atomic Habits', 'category' => 'Books', 'price' => 109000, 'stock' => 150],
      ['name' => 'Rich Dad Poor Dad', 'category' => 'Books', 'price' => 99000, 'stock' => 200],
      ['name' => 'The Pragmatic Programmer', 'category' => 'Books', 'price' => 219000, 'stock' => 35],
    ];

    foreach ($products as $index => $product) {
      Product::create([
        'name' => $product['name'],
        'sku' => 'PRD-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
        'description' => 'Deskripsi lengkap untuk produk ' . $product['name'] . '. Produk berkualitas tinggi dengan garansi resmi.',
        'category' => $product['category'],
        'price' => $product['price'],
        'stock' => $product['stock'],
        'status' => $statuses[array_rand($statuses)],
      ]);
    }
  }
}
