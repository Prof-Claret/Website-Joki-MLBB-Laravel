<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">My Dashboard</h2>
            <a href="{{ route('orders.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                + New Order
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Orders</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM5 16a2 2 0 11 0 4 2 2 0 010-4zm7 0a2 2 0 11 0 4 2 2 0 010-4z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Pending</p>
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
                            <p class="text-gray-500 text-sm">In Progress</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $stats['in_progress'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Completed</p>
                            <p class="text-3xl font-bold text-green-600">{{ $stats['completed_orders'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-green-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                        <div class="p-6 hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('orders.show', $order) }}'">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-sm font-medium text-blue-600">{{ $order->order_number }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $order->game->name }} - {{ $order->service->name }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium rounded
                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $order->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-4 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 text-xs">Worker</p>
                                    <p class="text-gray-900 font-medium">
                                        {{ $order->worker?->name ?? 'Not Assigned' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Progress</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $order->worker_progress }}%"></div>
                                    </div>
                                    <p class="text-gray-900 font-medium text-xs mt-1">{{ $order->worker_progress }}%</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Price</p>
                                    <p class="text-gray-900 font-medium">Rp {{ number_format($order->price, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Payment</p>
                                    <span class="px-2 py-1 text-xs font-medium rounded
                                        {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->payment_status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    ">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            <p>No orders yet. <a href="{{ route('orders.create') }}" class="text-blue-600 hover:text-blue-900">Create one now!</a></p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
