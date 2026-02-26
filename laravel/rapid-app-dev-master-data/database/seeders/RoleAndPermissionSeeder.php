<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RoleAndPermissionSeeder extends Seeder
{
  public function run(): void
  {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $permissions = [
      'view products',
      'create products',
      'edit products',
      'delete products',
      'view suppliers',
      'create suppliers',
      'edit suppliers',
      'delete suppliers',
      'view customers',
      'create customers',
      'edit customers',
      'delete customers',
      'view purchases',
      'create purchases',
      'view sales',
      'create sales',
    ];

    foreach ($permissions as $permission) {
      Permission::findOrCreate($permission);
    }

    $adminRole = Role::findOrCreate('admin');
    $adminRole->syncPermissions(Permission::all());

    $staffRole = Role::findOrCreate('staff');
    $staffRole->syncPermissions([
      'view products',
      'view suppliers',
      'view customers',
      'view purchases',
      'create purchases',
      'view sales',
      'create sales',
    ]);

    $admin = User::updateOrCreate(
      ['email' => 'admin@example.com'],
      [
        'name' => 'Admin User',
        'password' => Hash::make('password'),
      ]
    );
    $admin->assignRole($adminRole);

    $staff = User::updateOrCreate(
      ['email' => 'staff@example.com'],
      [
        'name' => 'Staff User',
        'password' => Hash::make('password'),
      ]
    );
    $staff->assignRole($staffRole);

    $p1 = Product::updateOrCreate(['sku' => 'LAP-001'], ['name' => 'Laptop High End', 'price' => 15000000, 'stock' => 0]);
    $p2 = Product::updateOrCreate(['sku' => 'MOU-001'], ['name' => 'Wireless Mouse', 'price' => 250000, 'stock' => 0]);
    $p3 = Product::updateOrCreate(['sku' => 'KEY-001'], ['name' => 'Mechanical Keyboard', 'price' => 1200000, 'stock' => 0]);

    $s1 = Supplier::updateOrCreate(['name' => 'PT Teknologi Jaya'], ['phone' => '08123456789', 'address' => 'Jakarta Pusat']);
    $s2 = Supplier::updateOrCreate(['name' => 'Global Parts Ltd'], ['phone' => '0219876543', 'address' => 'Surabaya']);

    $c1 = Customer::updateOrCreate(['name' => 'Budi Santoso'], ['phone' => '085551234', 'address' => 'Bandung']);
    $c2 = Customer::updateOrCreate(['name' => 'Siti Aminah'], ['phone' => '087779876', 'address' => 'Yogyakarta']);

    DB::transaction(function () use ($p1, $p2, $s1, $c1) {
      $purchase = Purchase::create([
        'supplier_id' => $s1->id,
        'date' => now()->subDays(5),
        'total_amount' => (10 * 14000000) + (20 * 200000),
      ]);
      $purchase->items()->create(['product_id' => $p1->id, 'quantity' => 10, 'price' => 14000000]);
      $purchase->items()->create(['product_id' => $p2->id, 'quantity' => 20, 'price' => 200000]);
      $p1->increment('stock', 10);
      $p2->increment('stock', 20);

      $sale = Sale::create([
        'customer_id' => $c1->id,
        'date' => now()->subDays(2),
        'total_amount' => (2 * 15000000) + (5 * 250000),
      ]);
      $sale->items()->create(['product_id' => $p1->id, 'quantity' => 2, 'price' => 15000000]);
      $sale->items()->create(['product_id' => $p2->id, 'quantity' => 5, 'price' => 250000]);
      $p1->decrement('stock', 2);
      $p2->decrement('stock', 5);
    });
  }
}
