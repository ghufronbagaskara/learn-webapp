@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
  <div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <div class="p-12 text-center">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 mb-6">
          <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </div>

        <h2 class="text-3xl font-bold text-red-600 mb-4">Payment Failed</h2>

        <p class="text-lg text-gray-700 mb-4">We couldn't process your payment.</p>

        <p class="text-gray-500 mb-8">
          Something went wrong during the payment process. Please try again or contact support if the problem persists.
        </p>

        <div class="flex justify-center space-x-4">
          <a href="{{ route('orders.index') }}"
            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            📦 Back to Orders
          </a>
          <a href="{{ route('payments.index') }}"
            class="inline-flex items-center px-6 py-3 border border-gray-300 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            💰 View Payments
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection
