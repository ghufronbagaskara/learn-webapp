<x-admin-layout :page-title="$pageTitle" :header-title="$pageTitle">
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-base font-medium text-gray-700">User management</h2>
            <a wire:navigate href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">Create User</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wide text-xs">Name</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wide text-xs">Email</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-500 uppercase tracking-wide text-xs">Role</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-500 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td class="px-4 py-3 text-gray-800">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-gray-800">{{ ucfirst($user->role) }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a wire:navigate href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">Edit</a>
                                    <x-confirm-modal title="Delete user?" message="This user account will be removed permanently." trigger="Delete">
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">Delete</button>
                                        </form>
                                    </x-confirm-modal>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-gray-400">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
    </section>
</x-admin-layout>
