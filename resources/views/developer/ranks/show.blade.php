<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $rank->name }} - {{ $rank->game->name }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('developer.ranks.edit', $rank) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Edit
                </a>
                <form action="{{ route('developer.ranks.destroy', $rank) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition" onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                    <!-- Left: Icon & Basic Info -->
                    <div class="flex flex-col items-center text-center">
                        @if($rank->icon_path)
                        <img src="{{ Storage::url($rank->icon_path) }}" alt="{{ $rank->name }}" class="h-32 w-32 rounded mb-4">
                        @else
                        <div class="h-32 w-32 rounded bg-gray-200 mb-4 flex items-center justify-center text-4xl text-gray-600">-</div>
                        @endif

                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $rank->name }}</h3>
                        
                        <div class="space-y-2 text-sm text-gray-600 mb-4 w-full">
                            <div class="flex justify-between items-center px-4 py-2 bg-gray-50 rounded">
                                <span>Slug:</span>
                                <span class="font-mono font-medium text-gray-900">{{ $rank->slug }}</span>
                            </div>
                            <div class="flex justify-between items-center px-4 py-2 bg-gray-50 rounded">
                                <span>Star System:</span>
                                <span class="font-medium text-gray-900">{{ $rank->star_system }}</span>
                            </div>
                            <div class="flex justify-between items-center px-4 py-2 bg-gray-50 rounded">
                                <span>Sort Order:</span>
                                <span class="font-medium text-gray-900">{{ $rank->sort_order }}</span>
                            </div>
                        </div>

                        <div class="inline-block">
                            <span class="px-4 py-2 text-sm font-medium rounded-full
                                {{ $rank->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $rank->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <!-- Right: Star Range & Game Info -->
                    <div class="space-y-6">
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p class="text-sm text-gray-600 mb-1">Star Range</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ $rank->min_star }} <span class="text-base text-gray-600">to</span> {{ $rank->max_star }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $rank->max_star - $rank->min_star + 1 }} star{{ $rank->max_star - $rank->min_star != 0 ? 's' : '' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Game Information</h4>
                            <dl class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Game:</dt>
                                    <dd class="font-medium text-gray-900">{{ $rank->game->name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Description:</dt>
                                    <dd class="font-medium text-gray-900">{{ $rank->game->description }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Total Ranks:</dt>
                                    <dd class="font-medium text-gray-900">{{ $rank->game->ranks->count() }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-2">Timestamps</h4>
                            <dl class="space-y-1 text-xs text-gray-600">
                                <div>Created: <span class="font-medium">{{ $rank->created_at->format('d M Y H:i') }}</span></div>
                                <div>Updated: <span class="font-medium">{{ $rank->updated_at->format('d M Y H:i') }}</span></div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Other Ranks in Game -->
                <div class="border-t border-gray-200 p-6">
                    <h4 class="font-semibold text-gray-900 mb-4">Other Ranks in {{ $rank->game->name }}</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($rank->game->ranks->sortBy('min_star') as $otherRank)
                            <a href="{{ route('developer.ranks.show', $otherRank) }}" 
                                class="p-3 rounded-lg text-center transition
                                {{ $otherRank->id === $rank->id 
                                    ? 'bg-blue-100 border-2 border-blue-500' 
                                    : 'bg-gray-100 border-2 border-gray-300 hover:border-blue-300' }}">
                                @if($otherRank->icon_path)
                                <img src="{{ Storage::url($otherRank->icon_path) }}" alt="{{ $otherRank->name }}" class="h-10 w-10 rounded mx-auto mb-2">
                                @else
                                <div class="h-10 w-10 rounded bg-gray-300 mx-auto mb-2 flex items-center justify-center text-xs text-gray-600">-</div>
                                @endif
                                <p class="text-xs font-semibold text-gray-900">{{ $otherRank->name }}</p>
                                <p class="text-xs text-gray-600">{{ $otherRank->min_star }}-{{ $otherRank->max_star }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Back Link -->
            <div class="mt-6">
                <a href="{{ route('developer.ranks.index') }}" class="text-blue-600 hover:text-blue-900">
                    ← Back to Ranks
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
