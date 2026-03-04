<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run(): void {
    $customers = [
      [
        'name' => "Budi Santoso",
        'email' => "budi.santoso@example.com",
        'phone' => "081234567890",
        'address' => "Jl. Merdeka No. 123, Jakarta",
      ],
      [
        'name' => 'Siti Nurhaliza',
        'email' => 'siti.nur@yahoo.com',
        'phone' => '082345678901',
        'address' => 'Jl. Thamrin No. 45, Jakarta Selatan',
      ],
      [
        'name' => 'Ahmad Dhani',
        'email' => 'ahmad.dhani@hotmail.com',
        'phone' => '083456789012',
        'address' => 'Jl. Gatot Subroto No. 78, Bandung',
      ],
    ];

    foreach ($customers as $customer) {
      Customer::create($customer);
    }
  }
}
