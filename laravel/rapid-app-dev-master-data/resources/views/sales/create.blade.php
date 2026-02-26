<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Record Sale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-input-error :messages="$errors->get('items')" class="mb-4" />
                    
                    <form action="{{ route('sales.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <x-input-label for="customer_id" :value="__('Customer')" />
                                <select name="customer_id" id="customer_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="date" :value="__('Date')" />
                                <x-text-input id="date" class="block mt-1 w-full" type="date" name="date" value="{{ date('Y-m-d') }}" required />
                            </div>
                        </div>

                        <h3 class="font-bold mb-2">Sale Items</h3>
                        <div id="items-container">
                            <div class="grid grid-cols-5 gap-4 mb-2 item-row">
                                <div class="col-span-2">
                                    <x-input-label :value="__('Product')" />
                                    <select name="items[0][product_id]" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label :value="__('Quantity')" />
                                    <x-text-input type="number" name="items[0][quantity]" class="block mt-1 w-full" value="1" min="1" required />
                                </div>
                                <div>
                                    <x-input-label :value="__('Price')" />
                                    <x-text-input type="number" step="0.01" name="items[0][price]" class="block mt-1 w-full" required />
                                </div>
                                <div class="flex items-end">
                                    <button type="button" class="remove-item bg-red-500 text-white px-3 py-2 rounded-md mb-1 hidden">Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" id="add-item" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Add Item
                            </button>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Save Sale') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemIndex = 1;
            const container = document.getElementById('items-container');
            const addButton = document.getElementById('add-item');

            addButton.addEventListener('click', function() {
                const newRow = container.querySelector('.item-row').cloneNode(true);
                
                // Update names
                newRow.querySelectorAll('select, input').forEach(input => {
                    const name = input.getAttribute('name');
                    input.setAttribute('name', name.replace('[0]', `[${itemIndex}]`));
                    if (input.tagName === 'INPUT') input.value = input.getAttribute('type') === 'number' ? (input.name.includes('quantity') ? 1 : '') : '';
                });

                // Show remove button
                const removeBtn = newRow.querySelector('.remove-item');
                removeBtn.classList.remove('hidden');
                removeBtn.addEventListener('click', function() {
                    newRow.remove();
                });

                container.appendChild(newRow);
                itemIndex++;
            });
        });
    </script>
</x-app-layout>
