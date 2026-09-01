<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Developer Dashboard</h2>
            <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">Developer</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Games</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_games'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 011-1h12a1 1 0 011 1H3zm0 4h16v2H3V5zm0 4h16v2H3V9zm0 4h16v2H3v-2z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Ranks</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_ranks'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-yellow-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Services</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_services'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-green-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Orders</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                        </div>
                        <svg class="w-12 h-12 text-red-500 opacity-20" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM5 16a2 2 0 11 0 4 2 2 0 010-4zm7 0a2 2 0 11 0 4 2 2 0 010-4z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Management</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('developer.ranks.index') }}" class="block px-4 py-2 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 transition">
                            📊 Manage Ranks & Tiers
                        </a>
                        <a href="{{ route('developer.settings.index') }}" class="block px-4 py-2 bg-green-50 text-green-700 rounded hover:bg-green-100 transition">
                            ⚙️ Site Settings
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">System Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Database:</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Connected</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Cache:</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Active</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Queue:</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Ready</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
