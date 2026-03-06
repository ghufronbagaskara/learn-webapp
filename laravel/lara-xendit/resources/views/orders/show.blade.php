@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
  <div class="mb-6">
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-bold text-gray-900">📦 Order #{{ $order->id }}</h2>
      <div class="flex space-x-2">
        <a href="{{ route('orders.index') }}"
          class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
          ← Back
        </a>
        @if ($order->isPending())
          <a href="{{ route('orders.edit', $order) }}"
            class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700">
            ✏ Edit
          </a>
        @endif
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Order Information -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
        <h3 class="text-lg font-semibold text-gray-900">ℹ Order Information</h3>
      </div>
      <div class="p-6">
        <dl class="space-y-3">
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Order ID:</dt>
            <dd class="text-sm font-semibold text-gray-900">#{{ $order->id }}</dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Status:</dt>
            <dd>
              @if ($order->status === 'pending')
                <span
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
              @elseif($order->status === 'paid')
                <span
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
              @else
                <span
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
              @endif
            </dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Amount:</dt>
            <dd class="text-sm font-bold text-blue-600">Rp {{ number_format($order->amount, 0, ',', '.') }}</dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Description:</dt>
            <dd class="text-sm text-gray-900">{{ $order->description }}</dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Created At:</dt>
            <dd class="text-sm text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- Customer Information -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <div class="px-6 py-4 bg-green-50 border-b border-green-100">
        <h3 class="text-lg font-semibold text-gray-900">👤 Customer Information</h3>
      </div>
      <div class="p-6">
        <dl class="space-y-3">
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Name:</dt>
            <dd class="text-sm text-gray-900">{{ $order->customer_name }}</dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Email:</dt>
            <dd class="text-sm text-gray-900">{{ $order->customer_email }}</dd>
          </div>
          <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-500">Phone:</dt>
            <dd class="text-sm text-gray-900">{{ $order->customer_phone }}</dd>
          </div>
        </dl>
      </div>
    </div>
  </div>

  <!-- Payment Actions -->
  @if ($order->isPending() && !$order->hasPendingPayment())
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded">
      <div class="flex justify-between items-center">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm text-yellow-700"><strong>No payment created yet.</strong> Create a payment invoice
              to process this order.</p>
          </div>
        </div>
        <form action="{{ route('payments.create', $order) }}" method="POST">
          @csrf
          <button type="submit"
            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
            💳 Create Payment
          </button>
        </form>
      </div>
    </div>
  @endif

  <!-- Payment History -->
  <div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
      <h3 class="text-lg font-semibold text-gray-900">💰 Payment History</h3>
    </div>
    <div class="p-6">
      @if ($order->payments->isEmpty())
        <p class="text-gray-500">No payment records found.</p>
      @else
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment ID</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">External ID</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach ($order->payments as $payment)
                <tr>
                  <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                    #{{ $payment->id }}</td>
                  <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                    <code>{{ $payment->external_id }}</code>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">Rp
                    {{ number_format($payment->amount, 0, ',', '.') }}</td>
                  <td class="px-4 py-3 whitespace-nowrap">
                    <span
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $payment->status === 'PAID' ? 'bg-green-100 text-green-800' : ($payment->status === 'PENDING' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                      {{ $payment->status }}
                    </span>
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap text-sm">
                    @if ($payment->payment_method)
                      <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $payment->payment_method }}</span>
                    @else
                      <span class="text-gray-400">-</span>
                    @endif
                  </td>
                  <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                    {{ $payment->created_at->format('d M Y H:i') }}</td>
                  <td class="px-4 py-3 whitespace-nowrap text-sm">
                    <a href="{{ route('payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900">View</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
@endsection
