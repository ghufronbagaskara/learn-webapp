<?php // app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrderController extends Controller
{
  public function store(Request $request): JsonResponse
  {
    $this->validate($request, [
      'items' => 'required|array|min:1',
      'items.*.product_id' => 'required|exists:products,id',
      'items.*.quantity' => 'required|integer|min:1',
    ]);

    try {
      $order = DB::transaction(function () use ($request) {
        $totalPrice = 0;
        $normalizedItems = [];

        foreach ($request->items as $item) {
          $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();

          if (!$product) {
            throw new RuntimeException('Produk dengan ID ' . $item['product_id'] . ' tidak ditemukan.');
          }

          if ($product->stock < $item['quantity']) {
            throw new RuntimeException('Stok produk ' . $product->name . ' tidak mencukupi.');
          }

          $product->decrement('stock', $item['quantity']);

          $subtotal = (float) $product->price * (int) $item['quantity'];
          $totalPrice += $subtotal;

          $normalizedItems[] = [
            'product_id' => $product->id,
            'quantity' => (int) $item['quantity'],
            'price_per_unit' => $product->price,
          ];
        }

        $order = Order::create([
          'user_id' => auth()->id(),
          'total_price' => $totalPrice,
          'status' => 'pending',
        ]);

        foreach ($normalizedItems as $normalizedItem) {
          OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $normalizedItem['product_id'],
            'quantity' => $normalizedItem['quantity'],
            'price_per_unit' => $normalizedItem['price_per_unit'],
          ]);
        }

        return $order->load(['items.product']);
      });

      return response()->json([
        'success' => true,
        'message' => 'Order berhasil dibuat',
        'data' => $order,
      ], 201);
    } catch (RuntimeException $e) {
      return response()->json([
        'success' => false,
        'message' => 'Gagal membuat order',
        'errors' => $e->getMessage(),
      ], 422);
    }
  }

  public function index(): JsonResponse
  {
    $orders = Order::with(['items.product'])
      ->where('user_id', auth()->id())
      ->orderByDesc('id')
      ->get();

    return response()->json([
      'success' => true,
      'message' => 'Daftar order berhasil diambil',
      'data' => $orders,
    ], 200);
  }

  public function show(int $id): JsonResponse
  {
    $order = Order::with(['items.product', 'payment'])->find($id);

    if (!$order) {
      return response()->json([
        'success' => false,
        'message' => 'Order tidak ditemukan',
        'errors' => null,
      ], 404);
    }

    if ((int) $order->user_id !== (int) auth()->id()) {
      return response()->json([
        'success' => false,
        'message' => 'Akses ditolak',
        'errors' => null,
      ], 403);
    }

    return response()->json([
      'success' => true,
      'message' => 'Detail order berhasil diambil',
      'data' => $order,
    ], 200);
  }
}
