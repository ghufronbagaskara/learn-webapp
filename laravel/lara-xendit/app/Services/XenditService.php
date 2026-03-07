<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Str;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\Invoice as InvoiceObject;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\InvoicePaymentMethod;

class XenditService {
  private InvoiceApi $invoiceApi;
  public function __construct() {
    Configuration::setXenditKey(config('services.xendit.secret_key'));

    $this->invoiceApi = new InvoiceApi();
  }

  // create invoice for order
  public function createInvoice(Order $order, array $options = []) {
    $externalId = 'ORDER-' . $order->id . '-' . Str::random(8);

    // create request object for xendit
    $requestParams = new CreateInvoiceRequest([
      'external_id' => $externalId,
      'amount' => (float) $order->amount,
      'description' => (string) $order->description,
      'invoice_duration' => $options['invoice_duration'] ?? 86400,
      'customer' => [
        'given_names' => $order->customer_name,
        'email' => $order->customer_email,
        'mobile_number' => $order->customer_phone,
      ],
      'customer_notification_preference' => [
        'invoice_created' => ['email'],
        'invoice_reminder' => ['email'],
        'invoice_paid' => ['email'],
      ],
      'success_redirect_url' => route('payments.success'),
      'failure_redirect_url' => route('payments.failed'),
      'currency' => ('IDR'),
    ]);

    try {
      // call api using object method
      $invoice = $this->invoiceApi->createInvoice($requestParams);

      //access response using getter methods
      $payment = Payment::create([
        'order_id' => $order->id,
        'xendit_invoice_id' => $invoice->getId(),
        'external_id' => $externalId,
        'invoice_url' => $invoice->getInvoiceUrl(),
        'amount' => $invoice->getAmount(),
        'currency' => $invoice->getCurrency(),
        'status' => $this->mapXenditStatus((string) $invoice->getStatus()),
        'expired_at' => $this->formatDateTime($invoice->getExpiryDate()),
        'xendit_response' => $this->invoiceToArray($invoice),
      ]);

      return $payment;
    } catch (Exception $e) {
      throw new Exception('Failed to create Xendit invoice: ' . $e->getMessage());
    }
  }

  public function getInvoice(string $invoiceId) {
    try {
      return $this->invoiceApi->getInvoiceById($invoiceId);
    } catch (Exception $e) {
      throw new Exception('Failed to retrieve Xendit invoice: ' . $e->getMessage());
    }
  }

  public function expireInvoice(string $invoiceId) {
    try {
      return $this->invoiceApi->expireInvoice($invoiceId);
    } catch (Exception $e) {
      throw new Exception('Failed to expire Xendit invoice: ' . $e->getMessage());
    }
  }

  // handle xendit webhook callback
  public function handleCallback(array $data) {
    $payment = Payment::where('xendit_invoice_id', $data['id'])->first();

    if (!$payment) {
      return null;
    }

    $status = $this->mapXenditStatus($data['status']);
    $payment->update([
      'status' => $status,
      'payment_method' => $data['payment_method'] ?? null,
      'payment_channel' => $data['payment_channel'] ?? null,
      'xendit_response' => $data
    ]);

    if ($status === 'PAID') {
      $payment->markAsPaid(
        $data['payment_method'] ?? null,
        $data['payment_channel'] ?? null,
      );
    }

    if ($status === 'EXPIRED') {
      $payment->markAsExpired();
    }

    return $payment;
  }

  // map xendit status to our internal status 
  private function mapXenditStatus(string $xenditStatus) {
    return match ($xenditStatus) {
      'PENDING' => 'PENDING',
      'PAID', 'SETTLED' => 'PAID',
      'EXPIRED' => 'EXPIRED',
      default => 'PENDING',
    };
  }

  // convert invoice object array for storage
  private function invoiceToArray(InvoiceObject $invoice) {
    return [
      'id' => $invoice->getId(),
      'external_id' => $invoice->getExternalId(),
      'user_id' => $invoice->getUserId(),
      'status' => $invoice->getStatus(),
      'merchant_name' => $invoice->getMerchantName(),
      'amount' => $invoice->getAmount(),
      'player_email' => $invoice->getPayerEmail(),
      'description' => $invoice->getDescription(),
      'expiry_date' => $this->formatDateTime($invoice->getExpiryDate()),
      'invoice_url' => $invoice->getInvoiceUrl(),
      'available_banks' => $invoice->getAvailableBanks(),
      'available_retail_outlets' => $invoice->getAvailableRetailOutlets(),
      'available_ewallets' => $invoice->getAvailableEwallets(),
      'should_exclude_credit_card' => $invoice->getShouldExcludeCreditCard(),
      'should_send_email' => $invoice->getShouldSendEmail(),
      'created' => $this->formatDateTime($invoice->getCreated()),
      'updated' => $this->formatDateTime($invoice->getUpdated()),
      'currency' => $invoice->getCurrency(),
      'payment_method' => $this->formatPaymentMethod($invoice->getPaymentMethod()),
    ];
  }

  private function parseDateTime($dateTime): ?\DateTime {
    if ($dateTime instanceof \DateTime) {
      return $dateTime;
    }

    if (is_string($dateTime)) {
      return new \DateTime($dateTime);
    }

    return null;
  }

  private function formatDateTime($dateTime): ?string {
    if ($dateTime instanceof \DateTime) {
      return $dateTime->format('Y-m-d H:i:s');
    }

    return null;
  }

  private function formatPaymentMethod($paymentMethod): ?string {
    if ($paymentMethod instanceof InvoicePaymentMethod) {
      return (string) $paymentMethod;
    }

    return null;
  }
}
