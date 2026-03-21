<?php // app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
  public function index(): JsonResponse
  {
    $products = Cache::remember('products_list', 3600, function () {
      return Product::all();
    });

    return response()->json([
      'success' => true,
      'message' => 'Daftar produk berhasil diambil',
      'data' => $products,
    ], 200);
  }

  public function show(int $id): JsonResponse
  {
    $product = Product::find($id);

    if (!$product) {
      return response()->json([
        'success' => false,
        'message' => 'Produk tidak ditemukan',
        'errors' => null,
      ], 404);
    }

    return response()->json([
      'success' => true,
      'message' => 'Detail produk berhasil diambil',
      'data' => $product,
    ], 200);
  }

  public function store(Request $request): JsonResponse
  {
    $this->validate($request, [
      'name' => 'required|string|max:200',
      'description' => 'nullable|string',
      'price' => 'required|numeric|min:0',
      'stock' => 'required|integer|min:0',
    ]);

    $product = Product::create($request->only(['name', 'description', 'price', 'stock']));
    Cache::forget('products_list');

    return response()->json([
      'success' => true,
      'message' => 'Produk berhasil dibuat',
      'data' => $product,
    ], 201);
  }

  public function update(Request $request, int $id): JsonResponse
  {
    $product = Product::find($id);

    if (!$product) {
      return response()->json([
        'success' => false,
        'message' => 'Produk tidak ditemukan',
        'errors' => null,
      ], 404);
    }

    $this->validate($request, [
      'name' => 'sometimes|string|max:200',
      'description' => 'sometimes|nullable|string',
      'price' => 'sometimes|numeric|min:0',
      'stock' => 'sometimes|integer|min:0',
    ]);

    $product->update($request->only(['name', 'description', 'price', 'stock']));
    Cache::forget('products_list');

    return response()->json([
      'success' => true,
      'message' => 'Produk berhasil diperbarui',
      'data' => $product,
    ], 200);
  }

  public function destroy(int $id): JsonResponse
  {
    $product = Product::find($id);

    if (!$product) {
      return response()->json([
        'success' => false,
        'message' => 'Produk tidak ditemukan',
        'errors' => null,
      ], 404);
    }

    $product->delete();
    Cache::forget('products_list');

    return response()->json([
      'success' => true,
      'message' => 'Produk berhasil dihapus',
      'data' => null,
    ], 200);
  }
}
