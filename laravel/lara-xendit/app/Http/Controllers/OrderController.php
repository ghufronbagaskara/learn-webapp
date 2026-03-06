<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller {
  public function index() {
    $orders = Order::with('latestPayment')
      ->latest()
      ->paginate(15);

    return view('orders.index', compact('orders'));
  }

  public function create() {
    return view('orders.create');
  }

  public function store(Request $request) {
    $validated = $request->validate([
      'customer_name' => 'required|string|max:255',
      'customer_email' => 'required|email|max:255',
      'customer_phone' => 'required|string|max:20',
      'amount' => 'required|numeric|min:100000',
      'description' => 'required|string|max:1000',
    ]);

    $order = Order::create($validated);

    return redirect()
      ->route('orders.show', $order)
      ->with('success', 'Order created successfully!');
  }

  public function show(Order $order) {
    $order->load('payments');

    return view('orders.show', compact('order'));
  }

  public function edit(Order $order) {
    $order->load('payments');

    return view('orders.edit', compact('order'));
  }

  public function update(Request $request, Order $order) {
    if ($order->isPaid()) {
      return back()->with('error', 'Cannot update a paid order');
    }

    $validated = $request->validate([
      'customer_name' => 'required|string|max:255',
      'customer_email' => 'required|email|max:255',
      'customer_phone' => 'required|string|max:20',
      'amount' => 'required|numeric|min:100000',
      'description' => 'required|string|max:1000',
    ]);

    $order->update($validated);

    return redirect()
      ->route('orders.show', $order)
      ->with('success', 'Order updated successfully!');
  }

  public function destroy(Order $order) {
    if ($order->isPaid()) {
      return back()->with('error', 'Cannot delete a paid order');
    }

    $order->delete();

    return redirect()
      ->route('orders.index')
      ->with('success', "Order deleted succesfully!");
  }
}
