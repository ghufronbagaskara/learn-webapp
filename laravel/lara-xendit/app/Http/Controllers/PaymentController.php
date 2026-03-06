<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\XenditService;
use Exception;
use Illuminate\Http\Request;

class PaymentController extends Controller {
  public function __construct(
    private readonly XenditService $xenditService
  ) {
  }

  public function index() {
    $payments = Payment::with('order')
      ->latest()
      ->paginate(15);

    return view('payments.index', compact('payments'));
  }

  public function show(Payment $payment) {
    $payment->load('order');

    return view('payments.show', compact('payment'));
  }

  public function create(Order $order) {
    try {
      if ($order->hasPendingPayment()) {
        return back()->with('error', 'Order already has pending payment');
      }

      if ($order->isPaid()) {
        return back()->with('error', 'Order already paid');
      }

      $payment = $this->xenditService->createInvoice($order);

      return redirect()
        ->route('payments.show', $payment)
        ->with('success', 'Payment invoice created successfully!');
    } catch (Exception $e) {
      return back()->with('error', 'Failed to create payment: ' . $e->getMessage());
    }
  }

  public function checkStatus(Payment $payment) {
    try {
      $invoiceObject = $this->xenditService->getInvoice($payment->xendit_invoice_id);

      $invoiceData = [
        'id' => $invoiceObject->getId(),
        'status' => $invoiceObject->getStatus(),
        'payment_method' => $invoiceObject->getPaymentMethod() ? (string) $invoiceObject->getPaymentMethod() : null,
        // payment_channel is only available in webhook
        'payment_channel' => null,
      ];

      $this->xenditService->handleCallback($invoiceData);

      return back()->with('success', 'Payment status updated!');
    } catch (Exception $e) {
      return back()->with('error', 'Failed to check payment status: ' . $e->getMessage());
    }
  }

  public function success(Request $request) {
    return view('payments.success');
  }

  public function failed(Request $request) {
    return view('payments.failed');
  }
}
