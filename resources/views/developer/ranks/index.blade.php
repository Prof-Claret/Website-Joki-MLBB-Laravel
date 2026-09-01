<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Game Ranks Management</h2>
            <a href="{{ route('developer.ranks.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + Create Rank
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @forelse($games as $game)
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $game->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $game->description }}</p>
                </div>
                
                @if($game->ranks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Slug</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Star Range</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($game->ranks->sortBy('min_star') as $rank)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($rank->icon_path)
                                        <img src="{{ Storage::url($rank->icon_path) }}" alt="{{ $rank->name }}" class="h-8 w-8 rounded mr-2">
                                        @else
                                        <div class="h-8 w-8 rounded bg-gray-200 mr-2 flex items-center justify-center text-xs text-gray-600">-</div>
                                        @endif
                                        <span class="font-medium text-gray-900">{{ $rank->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $rank->slug }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $rank->min_star }} - {{ $rank->max_star }} ⭐</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        {{ $rank->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $rank->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('developer.ranks.show', $rank) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                    <a href="{{ route('developer.ranks.edit', $rank) }}" class="text-green-600 hover:text-green-900">Edit</a>
                                    <form action="{{ route('developer.ranks.destroy', $rank) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-6 text-center text-gray-500">
                    <p>No ranks created for this game yet.</p>
                </div>
                @endif
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                <p>No games available. Please create games first.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
