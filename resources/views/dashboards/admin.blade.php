<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Admin Dashboard</h2>
            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">Admin</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Pending Orders</p>
                            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-yellow-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Active Orders</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $stats['active_orders'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H4a2.5 2.5 0 00-2.5 2.5v10a2.5 2.5 0 002.5 2.5h12a2.5 2.5 0 002.5-2.5V4a2.5 2.5 0 00-2.5-2.5h-6.5m0 0V4a1.5 1.5 0 013 0v-1.5m-3 0V4a1.5 1.5 0 00-3 0v-1.5"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Completed Orders</p>
                            <p class="text-3xl font-bold text-green-600">{{ $stats['completed_orders'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-green-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Revenue</p>
                            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                        </div>
                        <svg class="w-12 h-12 text-green-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.16 2.751A.75.75 0 019 2h1v2.5h2V2h1a.75.75 0 01.658 1.143l-3.5 6A.75.75 0 019.5 11h1v4.5h-1V11h-1v4.5H7v-4.5H4a.75.75 0 01-.658-1.143l3.5-6h.318V2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Game</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-blue-600">{{ $order->order_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $order->user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->game->name }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $order->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No orders yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
