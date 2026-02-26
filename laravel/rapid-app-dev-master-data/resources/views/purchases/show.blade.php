<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Purchase Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <p><strong>Supplier:</strong> {{ $purchase->supplier->name }}</p>
                        <p><strong>Date:</strong> {{ $purchase->date }}</p>
                        <p><strong>Total Amount:</strong> {{ number_format($purchase->total_amount, 2) }}</p>
                    </div>

                    <h3 class="font-bold mb-2">Items</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($purchase->items as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($item->quantity * $item->price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
