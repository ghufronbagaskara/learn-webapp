<?php

namespace App\Http\Controllers;

use App\Services\XenditService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller {
  public function __construct(
    private readonly XenditService $xenditService
  ) {
  }

  // xendit webhook handler
  public function xendit(Request $request) {
    try {
      $callbackToken = $request->header('X-CALLBACK-TOKEN');

      if ($callbackToken !== config('services.xendit.callback_token')) {
        Log::warning('Invalid Xendit callback token.', [
          'ip' => $request->ip(),
          'token' => $callbackToken,
        ]);

        return response()->json([
          'success' => false,
          'message' => 'Invalid callback token.'
        ], 401);
      }

      // log incoming webhook
      Log::info('Xendit webhook received', [
        'data' => $request->all()
      ]);

      // handle callback
      $payment = $this->xenditService->handleCallback($request->all());

      if (!$payment) {
        Log::warning('Payment not found in webhook', [
          'invoice_id' => $request->input('id')
        ]);

        return response()->json([
          'success' => false,
          'message' => 'Payment not found'
        ], 404);
      }

      Log::info('Xendit webhook processed successfully', [
        'payment_id' => $payment->id,
        'status' => $payment->status
      ]);

      return response()->json([
        'success' => true,
        'message' => 'Webhook processed successfully'
      ]);
    } catch (Exception $e) {
      Log::error('Xendit webhook error', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);

      return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
      ], 500);
    }
  }
}
