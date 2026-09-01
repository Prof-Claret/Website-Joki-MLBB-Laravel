<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Worker Dashboard</h2>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm font-medium">Worker</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Active Orders</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $stats['assigned_orders'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H4a2.5 2.5 0 00-2.5 2.5v10a2.5 2.5 0 002.5 2.5h12a2.5 2.5 0 002.5-2.5V4a2.5 2.5 0 00-2.5-2.5h-6.5"></path>
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

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Earnings</p>
                            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($stats['total_earnings'], 0, ',', '.') }}</p>
                        </div>
                        <svg class="w-12 h-12 text-green-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.16 2.751A.75.75 0 019 2h1v2.5h2V2h1a.75.75 0 01.658 1.143l-3.5 6A.75.75 0 019.5 11h1v4.5h-1V11h-1v4.5H7v-4.5H4a.75.75 0 01-.658-1.143l3.5-6h.318V2z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Pending Withdrawal</p>
                            <p class="text-3xl font-bold text-yellow-600">Rp {{ number_format($stats['pending_withdrawal'], 0, ',', '.') }}</p>
                        </div>
                        <svg class="w-12 h-12 text-yellow-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5.5 13a3 3 0 01.369-1.495 2 2 0 00-1.64-2.905A2 2 0 003 13a2 2 0 002.5 2zm0 0h7m0-2a3 3 0 01.369-1.495 2 2 0 00-1.64-2.905A2 2 0 0010 13a2 2 0 002.5 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Orders -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Active Orders</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($activeOrders as $order)
                        <div class="p-6 hover:bg-gray-50 cursor-pointer border-b" onclick="window.location='{{ route('orders.show', $order) }}'">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-sm font-medium text-blue-600">{{ $order->order_number }}</p>
                                    <p class="text-gray-900 font-semibold">{{ $order->game->name }} - {{ $order->service->name }}</p>
                                </div>
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">In Progress</span>
                            </div>
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Customer</p>
                                    <p class="text-gray-900 font-medium">{{ $order->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Progress</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $order->worker_progress }}%"></div>
                                    </div>
                                    <p class="text-gray-900 font-medium text-xs mt-1">{{ $order->worker_progress }}%</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Reward</p>
                                    <p class="text-gray-900 font-medium">Rp {{ number_format($order->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            <p>No active orders assigned to you.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
