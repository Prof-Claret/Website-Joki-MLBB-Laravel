<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Create New Order</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <form action="{{ route('orders.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Game & Service Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="game_id" class="block text-sm font-medium text-gray-700 mb-2">Game *</label>
                            <select id="game_id" name="game_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select a game</option>
                                @foreach($games as $game)
                                    <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                                @endforeach
                            </select>
                            @error('game_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">Service *</label>
                            <select id="service_id" name="service_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select a service</option>
                                @foreach($games as $game)
                                    @foreach($game->services as $service)
                                        <option value="{{ $service->id }}" data-price="{{ $service->base_price }}">
                                            {{ $service->name }} - Rp {{ number_format($service->base_price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('service_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Rank Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-200 pt-6">
                        <div>
                            <label for="rank_from_id" class="block text-sm font-medium text-gray-700 mb-2">Current Rank</label>
                            <select id="rank_from_id" name="rank_from_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select rank</option>
                                @foreach($games as $game)
                                    <optgroup label="{{ $game->name }}">
                                        @foreach($game->ranks->sortBy('min_star') as $rank)
                                            <option value="{{ $rank->id }}" {{ old('rank_from_id') == $rank->id ? 'selected' : '' }}>
                                                {{ $rank->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('rank_from_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="rank_to_id" class="block text-sm font-medium text-gray-700 mb-2">Target Rank</label>
                            <select id="rank_to_id" name="rank_to_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select rank</option>
                                @foreach($games as $game)
                                    <optgroup label="{{ $game->name }}">
                                        @foreach($game->ranks->sortBy('min_star') as $rank)
                                            <option value="{{ $rank->id }}" {{ old('rank_to_id') == $rank->id ? 'selected' : '' }}>
                                                {{ $rank->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('rank_to_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="border-t border-gray-200 pt-6 space-y-6">
                        <div>
                            <label for="wa_number" class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number *</label>
                            <input type="text" id="wa_number" name="wa_number" required value="{{ old('wa_number', auth()->user()->wa_number) }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('wa_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="account_credentials" class="block text-sm font-medium text-gray-700 mb-2">Account Credentials *</label>
                            <textarea id="account_credentials" name="account_credentials" required rows="4" 
                                placeholder="Email / Username&#10;Password&#10;(This will be encrypted)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('account_credentials') }}</textarea>
                            @error('account_credentials')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="request_hero" class="block text-sm font-medium text-gray-700 mb-2">Preferred Hero/Character</label>
                            <input type="text" id="request_hero" name="request_hero" value="{{ old('request_hero') }}" 
                                placeholder="e.g., Chou, Akai, Kaja"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('request_hero')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea id="notes" name="notes" rows="3" placeholder="Any special requests or notes..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Price & Payment -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <p class="text-sm text-gray-600">Base Price</p>
                                <p class="text-2xl font-bold text-gray-900" id="basePrice">Rp 0</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Service Fee</p>
                                <p class="text-2xl font-bold text-gray-900" id="serviceFee">Rp 0</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Price</p>
                                <p class="text-2xl font-bold text-blue-600" id="totalPrice">Rp 0</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                Create Order
                            </button>
                            <a href="{{ route('dashboard') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('service_id').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const basePrice = parseFloat(option.getAttribute('data-price') || 0);
            document.getElementById('basePrice').textContent = 'Rp ' + basePrice.toLocaleString('id-ID', { maximumFractionDigits: 0 });
            document.getElementById('totalPrice').textContent = 'Rp ' + basePrice.toLocaleString('id-ID', { maximumFractionDigits: 0 });
        });
    </script>
</x-app-layout>
