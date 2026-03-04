<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Symfony\Component\Clock\now;

class SalesOrderSeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run(): void {
    $customers = Customer::all();
    $products = Product::all();

    $statuses = ['Pending', 'Paid', 'Cancelled'];

    for ($i = 1; $i <= 20; $i++) {
      $salesOrder = SalesOrder::create([
        'customer_id' => $customers->random()->id,
        'order_date' => now(),
        'status' => $statuses[array_rand($statuses)],
        'total_amount' => 0,
      ]);

      $itemCount = rand(2, 5);
      for ($j = 0; $j < $itemCount; $j++) {
        $product = $products->random();
        $quantity = rand(1, 5);

        SalesOrderItem::create([
          'sales_order_id' => $salesOrder->id,
          'product_id' => $product->id,
          'quantity' => $quantity,
          'price' => $product->price,
          'subtotal' => $quantity * $product->price,
        ]);
      }
    }
  }
}
