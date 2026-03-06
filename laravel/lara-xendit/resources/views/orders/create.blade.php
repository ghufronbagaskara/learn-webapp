@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">➕ Create New Order</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Customer Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('customer_name') border-red-500 @enderror"
                            required>
                        @error('customer_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Customer Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('customer_email') border-red-500 @enderror"
                            required>
                        @error('customer_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Customer Phone <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}"
                            placeholder="+6281234567890"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('customer_phone') border-red-500 @enderror"
                            required>
                        @error('customer_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Format: +62xxx (with country code)</p>
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Amount (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" min="10000"
                            step="1000"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror"
                            required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Minimum: Rp 10,000</p>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                            required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('orders.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            ← Back
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            💾 Create Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
