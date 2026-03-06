@extends('layouts.app')

@section('title', 'Payment #' . $payment->id)

@section('content')
  <div class="mb-6">
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-bold text-gray-900">💰 Payment #{{ $payment->id }}</h2>
      <a href="{{ route('payments.index') }}"
        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        ← Back
      </a>
    </div>
  </div>

  <!-- Status Alert -->
  @if ($payment->isPending())
    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
              clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-yellow-700"><strong>Payment Pending</strong> - Waiting for customer to complete payment.
          </p>
        </div>
      </div>
    </div>
  @elseif($payment->isPaid())
    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
              clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-green-700"><strong>Payment Successful</strong> - This payment has been completed.</p>
        </div>
      </div>
    </div>
  @elseif($payment->isExpired())
    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
              clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-red-700"><strong>Payment Expired</strong> - This invoice has expired.</p>
        </div>
      </div>
    </div>
  @endif

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Payment Information -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
        <h3 class="text-lg font-semibold text-gray-900">💳 Payment Information</h3>
      </div>
      <div class="p-6">
        <dl class="space-y-3">
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Payment ID:</dt>
            <dd class="text-sm font-semibold text-gray-900">#{{ $payment->id }}</dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">External ID:</dt>
            <dd class="text-sm text-gray-900"><code
                class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $payment->external_id }}</code></dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Xendit Invoice ID:</dt>
            <dd class="text-sm text-gray-900"><code
                class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $payment->xendit_invoice_id }}</code></dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Amount:</dt>
            <dd class="text-sm font-bold text-blue-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Currency:</dt>
            <dd class="text-sm text-gray-900">{{ $payment->currency }}</dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Status:</dt>
            <dd>
              <span
                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $payment->status === 'PAID' ? 'bg-green-100 text-green-800' : ($payment->status === 'PENDING' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                {{ $payment->status }}
              </span>
            </dd>
          </div>
          @if ($payment->payment_method)
            <div class="flex justify-between">
              <dt class="text-sm font-medium text-gray-500">Payment Method:</dt>
              <dd><span
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $payment->payment_method }}</span>
              </dd>
            </div>
          @endif
          @if ($payment->payment_channel)
            <div class="flex justify-between">
              <dt class="text-sm font-medium text-gray-500">Payment Channel:</dt>
              <dd><span
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $payment->payment_channel }}</span>
              </dd>
            </div>
          @endif
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Created At:</dt>
            <dd class="text-sm text-gray-900">{{ $payment->created_at->format('d M Y H:i') }}</dd>
          </div>
          @if ($payment->paid_at)
            <div class="flex justify-between">
              <dt class="text-sm font-medium text-gray-500">Paid At:</dt>
              <dd class="text-sm text-green-600">{{ $payment->paid_at->format('d M Y H:i') }}</dd>
            </div>
          @endif
          @if ($payment->expired_at)
            <div class="flex justify-between">
              <dt class="text-sm font-medium text-gray-500">Expired At:</dt>
              <dd class="text-sm text-red-600">{{ $payment->expired_at->format('d M Y H:i') }}</dd>
            </div>
          @endif
        </dl>
      </div>
    </div>

    <!-- Order Information -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-green-50 border-b border-green-100">
        <h3 class="text-lg font-semibold text-gray-900">📦 Order Information</h3>
      </div>
      <div class="p-6">
        <dl class="space-y-3">
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Order ID:</dt>
            <dd class="text-sm">
              <a href="{{ route('orders.show', $payment->order) }}"
                class="text-blue-600 hover:text-blue-900 font-semibold">
                #{{ $payment->order->id }}
              </a>
            </dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Customer:</dt>
            <dd class="text-sm text-gray-900">{{ $payment->order->customer_name }}</dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Email:</dt>
            <dd class="text-sm text-gray-900">{{ $payment->order->customer_email }}</dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Phone:</dt>
            <dd class="text-sm text-gray-900">{{ $payment->order->customer_phone }}</dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Description:</dt>
            <dd class="text-sm text-gray-900">{{ $payment->order->description }}</dd>
          </div>
        </dl>
      </div>
    </div>
  </div>

  <!-- Invoice URL -->
  @if ($payment->isPending() && $payment->invoice_url)
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
      <div class="px-6 py-4 bg-green-50 border-b border-green-100">
        <h3 class="text-lg font-semibold text-gray-900">🔗 Invoice URL</h3>
      </div>
      <div class="p-6">
        <div class="flex space-x-2 mb-2">
          <input type="text" id="invoiceUrl" value="{{ $payment->invoice_url }}" readonly
            class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-sm">
          <button onclick="copyUrl()"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
            📋 Copy
          </button>
          <a href="{{ $payment->invoice_url }}" target="_blank"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
            🔗 Open
          </a>
        </div>
        <p class="text-sm text-gray-500">Share this URL with the customer to complete payment.</p>
      </div>
    </div>
  @endif

  <!-- Actions -->
  <div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-6">
      <div class="flex space-x-3">
        @if ($payment->isPending())
          <form action="{{ route('payments.check-status', $payment) }}" method="POST">
            @csrf
            <button type="submit"
              class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
              🔄 Check Status
            </button>
          </form>
        @endif

        <a href="{{ route('orders.show', $payment->order) }}"
          class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
          📦 View Order
        </a>
      </div>
    </div>
  </div>

  <script>
    function copyUrl() {
      const input = document.getElementById('invoiceUrl');
      input.select();
      input.setSelectionRange(0, 99999);

      navigator.clipboard.writeText(input.value).then(() => {
        alert('✓ Invoice URL copied to clipboard!');
      }).catch(err => {
        console.error('Failed to copy:', err);
      });
    }
  </script>
@endsection
