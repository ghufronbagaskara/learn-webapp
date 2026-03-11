<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller {
  // resource list
  public function index() {
    return Inertia::render('products/index');
  }

  // server side datatable endpoint 
  public function datatable(Request $request) {
    $query = Product::query();

    // global search
    if ($search = $request->input('search.value')) {
      $query->where(function ($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('sku', 'like', "%{$search}%")
          ->orWhere('category', 'like', "%{$search}%")
          ->orWhere('status', 'like', "%{$search}%");
      });
    }

    $totalRecords = Product::count();
    $filteredRecords = $query->count();

    // ordering 
    $orderColumnIndex = $request->input('order.0.column', 0);
    $orderDir = $request->input('order.0.dir', 'asc');
    $columns = ['id', 'name', 'sku', 'category', 'price', 'stock', 'status', 'created_at'];
    $orderColumn = $columns[$orderColumnIndex] ?? 'id';
    $query->orderBy($orderColumn, $orderDir);

    // pagination
    $start = $request->input('start', 0);
    $length = $request->input('length', 10);
    $products = $query->skip($start)->take($length)->get();

    $data = $products->map(function ($product, $index) use ($start) {
      return [
        'DT_RowId' => 'row_' . $product->id,
        'no' => $start + $index + 1,
        'id' => $product->id,
        'name' => $product->name,
        'sku' => $product->sku,
        'category' => $product->category,
        'price' => $product->price,
        'formatted_price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
        'stock' => $product->stock,
        'status' => $product->status,
        'created_at' => $product->created_at->format('d M Y'),
      ];
    });

    return response()->json([
      'draw' => intval($request->input('draw')),
      'recordsTotal' => $totalRecords,
      'recordsFiltered' => $filteredRecords,
      'data' => $data
    ]);
  }

  public function store(Request $request) {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'sku' => 'required|string|unique:products,sku',
      'category' => 'required|string',
      'price' => 'required|numeric|min:0',
      'stock' => 'required|integer|min:0',
      'status' => 'required|in:active,inactive,draft',
      'description' => 'nullable|string',
    ]);

    Product::create($validated);

    return redirect()->back()->with('success', 'Produk berhasil ditambahkan');
  }

  public function update(Request $request, Product $product) {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'sku' => 'required|string|unique:products,sku,' . $product->id,
      'category' => 'required|string',
      'price' => 'required|numeric|min:0',
      'stock' => 'required|integer|min:0',
      'status' => 'required|in:active,inactive,draft',
      'description' => 'nullable|string',
    ]);

    $product->update($validated);

    return redirect()->back()->with('success', 'Produk berhasil diperbarui');
  }

  public function destroy(Product $product) {
    $product->delete();

    return redirect()->back()->with('success', 'Produk berhasil dihapus');
  }
}
