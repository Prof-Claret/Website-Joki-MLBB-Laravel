<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Order Tracking: {{ $order->order_number }}</h2>
            <span class="px-3 py-1 text-xs font-medium rounded-full
                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $order->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
            ">
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Order Status Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900">Order Created</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if($order->status !== 'pending')
                    <div class="flex items-center">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full {{ $order->status !== 'pending' ? 'bg-green-100' : 'bg-gray-200' }}">
                            <svg class="h-6 w-6 {{ $order->status !== 'pending' ? 'text-green-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900">Payment Confirmed</p>
                            <p class="text-xs text-gray-500">Payment status: <span class="font-medium text-green-600">{{ ucfirst($order->payment_status) }}</span></p>
                        </div>
                    </div>
                    @endif

                    @if($order->status === 'in_progress' || $order->status === 'completed')
                    <div class="flex items-center">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                            <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.5 1.5H4a2.5 2.5 0 00-2.5 2.5v10a2.5 2.5 0 002.5 2.5h12a2.5 2.5 0 002.5-2.5V4a2.5 2.5 0 00-2.5-2.5h-6.5"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900">In Progress</p>
                            <p class="text-xs text-gray-500">Worker: <span class="font-medium">{{ $order->worker?->name ?? 'Waiting for assignment' }}</span></p>
                        </div>
                    </div>
                    @endif

                    @if($order->status === 'completed')
                    <div class="flex items-center">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900">Completed</p>
                            <p class="text-xs text-gray-500">{{ $order->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Information -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Game</p>
                                <p class="text-base font-medium text-gray-900">{{ $order->game->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Service</p>
                                <p class="text-base font-medium text-gray-900">{{ $order->service->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Rank From</p>
                                <p class="text-base font-medium text-gray-900">{{ $order->rankFrom?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Rank To</p>
                                <p class="text-base font-medium text-gray-900">{{ $order->rankTo?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tracking Code</p>
                                <p class="text-base font-medium text-blue-600 font-mono">{{ $order->tracking_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Price</p>
                                <p class="text-base font-medium text-gray-900">Rp {{ number_format($order->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress -->
                    @if($order->status !== 'pending')
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Work Progress</h3>
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm text-gray-600">Completion</p>
                                <p class="text-sm font-medium text-gray-900">{{ $order->worker_progress }}%</p>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $order->worker_progress }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Proofs -->
                    @if($order->proofs->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Work Proofs</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($order->proofs as $proof)
                            <div class="rounded-lg overflow-hidden border border-gray-200">
                                <img src="{{ Storage::url($proof->file_path) }}" alt="Proof" class="w-full h-40 object-cover">
                                <div class="p-3">
                                    <p class="text-xs text-gray-600">{{ $proof->caption }}</p>
                                    <p class="text-xs text-gray-400">{{ $proof->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Worker Info -->
                    @if($order->worker)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Worker</h3>
                        <div class="text-center">
                            <div class="h-12 w-12 rounded-full bg-blue-100 mx-auto mb-3 flex items-center justify-center">
                                <span class="text-lg font-bold text-blue-600">{{ strtoupper(substr($order->worker->name, 0, 1)) }}</span>
                            </div>
                            <p class="font-medium text-gray-900">{{ $order->worker->name }}</p>
                            @if($order->worker->reviewsReceived->avg('rating'))
                            <div class="mt-2 flex items-center justify-center">
                                <span class="text-yellow-400">★</span>
                                <span class="ml-1 text-sm text-gray-600">{{ number_format($order->worker->reviewsReceived->avg('rating'), 1) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Payment Status -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Amount</span>
                                <span class="font-medium text-gray-900">Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Method</span>
                                <span class="font-medium text-gray-900">{{ ucfirst($order->payment_method) }}</span>
                            </div>
                            <div class="pt-3 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Status</span>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        {{ $order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $order->payment_status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    ">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Section -->
                    @if($order->status === 'completed')
                    <div class="bg-white rounded-lg shadow p-6">
                        @if($order->customer_rating)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Rating</h3>
                            <div class="flex items-center mb-2">
                                <span class="text-2xl">
                                    @for($i = 0; $i < 5; $i++)
                                        @if($i < $order->customer_rating)
                                            <span class="text-yellow-400">★</span>
                                        @else
                                            <span class="text-gray-300">★</span>
                                        @endif
                                    @endfor
                                </span>
                            </div>
                            <p class="text-gray-600">{{ $order->customer_review }}</p>
                        </div>
                        @else
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rate This Order</h3>
                        <form action="{{ route('orders.review', $order) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                <div class="flex gap-2">
                                    @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="rating-btn text-3xl text-gray-300 hover:text-yellow-400 transition" data-rating="{{ $i }}">★</button>
                                    @endfor
                                </div>
                                <input type="hidden" id="rating" name="rating" value="">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
                                <textarea name="comment" rows="3" placeholder="Share your feedback..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                Submit Rating
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.rating-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                document.getElementById('rating').value = rating;
                document.querySelectorAll('.rating-btn').forEach((b, i) => {
                    b.classList.toggle('text-yellow-400', i < rating);
                    b.classList.toggle('text-gray-300', i >= rating);
                });
            });
        });
    </script>
</x-app-layout>
