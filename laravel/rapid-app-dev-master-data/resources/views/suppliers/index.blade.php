<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Suppliers') }}
            </h2>
            @can('create suppliers')
            <a href="{{ route('suppliers.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Supplier
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $supplier->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $supplier->phone }}</td>
                                <td class="px-6 py-4">{{ $supplier->address }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @can('edit suppliers')
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
