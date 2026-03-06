@extends('layouts.app')

@section('title', 'Payments')

@section('content')
  <div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">💰 Payments</h2>
  </div>

  @if ($payments->isEmpty())
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
              clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-blue-700">No payments found. Create an order and generate payment invoice first.</p>
        </div>
      </div>
    </div>
  @else
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">External ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Channel</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach ($payments as $payment)
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $payment->id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <a href="{{ route('orders.show', $payment->order) }}" class="text-blue-600 hover:text-blue-900">
                  Order #{{ $payment->order_id }}
                </a>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $payment->external_id }}</code>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                Rp {{ number_format($payment->amount, 0, ',', '.') }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $payment->status === 'PAID' ? 'bg-green-100 text-green-800' : ($payment->status === 'PENDING' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                  {{ $payment->status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                @if ($payment->payment_method)
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $payment->payment_method }}</span>
                @else
                  <span class="text-gray-400">-</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                @if ($payment->payment_channel)
                  <span
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $payment->payment_channel }}</span>
                @else
                  <span class="text-gray-400">-</span>
                @endif
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ $payment->created_at->format('d M Y H:i') }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <a href="{{ route('payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900">View</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4">
      {{ $payments->links() }}
    </div>
  @endif
@endsection
