<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder {
  public function run(): void {
    $orders = [
      [
        'customer_name' => 'Budi Santoso',
        'customer_email' => 'budi.santoso@gmail.com',
        'customer_phone' => '+6281234567890',
        'amount' => 500000,
        'description' => 'Pembelian Paket Website Standard',
        'status' => 'pending',
      ],
      [
        'customer_name' => 'Siti Nurhaliza',
        'customer_email' => 'siti.nur@yahoo.com',
        'customer_phone' => '+6282345678901',
        'amount' => 1500000,
        'description' => 'Pembelian Paket Website Premium + SEO',
        'status' => 'pending',
      ],
      [
        'customer_name' => 'Ahmad Dhani',
        'customer_email' => 'ahmad.dhani@hotmail.com',
        'customer_phone' => '+6283456789012',
        'amount' => 750000,
        'description' => 'Jasa Konsultasi Digital Marketing',
        'status' => 'pending',
      ],
      [
        'customer_name' => 'Dewi Lestari',
        'customer_email' => 'dewi.lestari@gmail.com',
        'customer_phone' => '+6284567890123',
        'amount' => 2000000,
        'description' => 'Development Mobile App Android',
        'status' => 'pending',
      ],
      [
        'customer_name' => 'Rina Wijaya',
        'customer_email' => 'rina.wijaya@outlook.com',
        'customer_phone' => '+6285678901234',
        'amount' => 350000,
        'description' => 'Design Logo & Branding Kit',
        'status' => 'pending',
      ],
      [
        'customer_name' => 'Agus Suprapto',
        'customer_email' => 'agus.suprapto@gmail.com',
        'customer_phone' => '+6286789012345',
        'amount' => 1200000,
        'description' => 'Pembuatan Toko Online E-commerce',
        'status' => 'paid',
      ],
      [
        'customer_name' => 'Linda Sari',
        'customer_email' => 'linda.sari@yahoo.com',
        'customer_phone' => '+6287890123456',
        'amount' => 600000,
        'description' => 'Landing Page + Google Ads Setup',
        'status' => 'paid',
      ],
      [
        'customer_name' => 'Bambang Pamungkas',
        'customer_email' => 'bambang.p@gmail.com',
        'customer_phone' => '+6288901234567',
        'amount' => 450000,
        'description' => 'Social Media Management 1 Bulan',
        'status' => 'pending',
      ],
      [
        'customer_name' => 'Maya Angelina',
        'customer_email' => 'maya.angel@gmail.com',
        'customer_phone' => '+6289012345678',
        'amount' => 900000,
        'description' => 'Custom WordPress Theme Development',
        'status' => 'pending',
      ],
      [
        'customer_name' => 'Rudi Hartono',
        'customer_email' => 'rudi.hartono@yahoo.com',
        'customer_phone' => '+6281122334455',
        'amount' => 1800000,
        'description' => 'Company Profile Website + Hosting 1 Tahun',
        'status' => 'pending',
      ],
    ];

    foreach ($orders as $order) {
      Order::create($order);
    }
  }
}
