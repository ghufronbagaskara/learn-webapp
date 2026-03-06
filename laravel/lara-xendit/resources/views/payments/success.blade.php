@extends('layouts.app')

@section('title', 'Payment Success')

@section('content')
  <div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
      <div class="p-12 text-center">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
          <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>

        <h2 class="text-3xl font-bold text-green-600 mb-4">Payment Successful!</h2>

        <p class="text-lg text-gray-700 mb-4">Your payment has been processed successfully.</p>

        <p class="text-gray-500 mb-8">
          Thank you for your payment. You will receive a confirmation email shortly.
        </p>

        <div class="flex justify-center space-x-4">
          <a href="{{ route('orders.index') }}"
            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            📦 View Orders
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
