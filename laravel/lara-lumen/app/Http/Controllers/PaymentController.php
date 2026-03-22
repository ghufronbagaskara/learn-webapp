<?php // app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class PaymentController extends Controller
{
  public function process(Request $request): JsonResponse
  {
    try {
      $this->validate($request, [
        'order_id' => 'required|exists:orders,id',
        'method' => 'required|in:bank_transfer,credit_card,e_wallet',
      ]);

      [$payment, $invoiceOrder] = DB::transaction(function () use ($request) {
        $order = Order::with(['user', 'items.product', 'payment'])
          ->lockForUpdate()
          ->findOrFail($request->order_id);

        if ((int) $order->user_id !== (int) auth()->id()) {
          throw new RuntimeException('Akses ditolak', 403);
        }

        if ($order->status === 'paid' || $order->payment) {
          throw new RuntimeException('Order sudah dibayar', 422);
        }

        $paymentRef = 'PAY-' . strtoupper(Str::random(20));
        $payment = Payment::create([
          'order_id' => $order->id,
          'amount' => $order->total_price,
          'method' => $request->method,
          'status' => 'success',
          'payment_ref' => $paymentRef,
        ]);

        $order->update(['status' => 'paid']);

        return [$payment, $order->fresh(['user', 'items.product', 'payment'])];
      });

      Mail::to($invoiceOrder->user->email)->send(new InvoiceMail($invoiceOrder));

      return response()->json([
        'success' => true,
        'message' => 'Pembayaran berhasil diproses',
        'data' => $payment,
      ], 201);
    } catch (RuntimeException $e) {
      $statusCode = in_array($e->getCode(), [403, 422], true) ? $e->getCode() : 422;

      Log::error('Payment failed', [
        'user_id' => auth()->id(),
        'order_id' => $request->order_id,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Proses pembayaran gagal',
        'errors' => $e->getMessage(),
      ], $statusCode);
    } catch (\Exception $e) {
      Log::error('Payment failed', [
        'user_id' => auth()->id(),
        'order_id' => $request->order_id,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
      ]);

      return response()->json([
        'success' => false,
        'message' => 'Proses pembayaran gagal',
        'errors' => $e->getMessage(),
      ], 500);
    }
  }

  public function show(int $order_id): JsonResponse
  {
    $order = Order::with('payment')->find($order_id);

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

    if (!$order->payment) {
      return response()->json([
        'success' => false,
        'message' => 'Data pembayaran tidak ditemukan',
        'errors' => null,
      ], 404);
    }

    return response()->json([
      'success' => true,
      'message' => 'Detail pembayaran berhasil diambil',
      'data' => $order->payment,
    ], 200);
  }
}
