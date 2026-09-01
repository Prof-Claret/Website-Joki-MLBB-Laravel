<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">Site Settings</h2>
            <button onclick="toggleCreateForm()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                + New Setting
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Create New Setting Form -->
            <div id="createForm" class="hidden bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Create New Setting</h3>
                <form action="{{ route('developer.settings.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="setting_key" class="block text-sm font-medium text-gray-700 mb-2">Setting Key *</label>
                            <input type="text" id="setting_key" name="setting_key" required 
                                placeholder="e.g., app_name, contact_email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                value="{{ old('setting_key') }}">
                            @error('setting_key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="group_name" class="block text-sm font-medium text-gray-700 mb-2">Group *</label>
                            <input type="text" id="group_name" name="group_name" required 
                                placeholder="e.g., app, contact, payment"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                value="{{ old('group_name') }}">
                            @error('group_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="setting_value" class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                        <textarea id="setting_value" name="setting_value" rows="3"
                            placeholder="Setting value"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('setting_value') }}</textarea>
                        @error('setting_value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                            <select id="type" name="type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select type</option>
                                <option value="string" {{ old('type') === 'string' ? 'selected' : '' }}>String</option>
                                <option value="text" {{ old('type') === 'text' ? 'selected' : '' }}>Text</option>
                                <option value="integer" {{ old('type') === 'integer' ? 'selected' : '' }}>Integer</option>
                                <option value="boolean" {{ old('type') === 'boolean' ? 'selected' : '' }}>Boolean</option>
                                <option value="json" {{ old('type') === 'json' ? 'selected' : '' }}>JSON</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="rounded">
                                Public
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                            Save Setting
                        </button>
                        <button type="button" onclick="toggleCreateForm()" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Settings List by Group -->
            @forelse($settings->groupBy('group_name') as $group => $groupSettings)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ ucfirst($group) }}</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($groupSettings as $setting)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-sm font-medium text-blue-600 font-mono">{{ $setting->setting_key }}</p>
                                <p class="text-xs text-gray-500 mt-1">Type: <span class="font-medium text-gray-700">{{ ucfirst($setting->type) }}</span></p>
                            </div>
                            <div class="flex gap-2">
                                <span class="px-2 py-1 text-xs font-medium rounded
                                    {{ $setting->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $setting->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($setting->is_public)
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded">
                                    Public
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded p-3 mb-3 break-words">
                            <p class="text-sm text-gray-900">
                                @if($setting->type === 'json')
                                    <code class="text-xs">{{ json_encode(json_decode($setting->setting_value), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code>
                                @else
                                    {{ $setting->setting_value ?? '(empty)' }}
                                @endif
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <button onclick="editSetting({{ $setting->id }})" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                Edit
                            </button>
                            <form action="{{ route('developer.settings.update', $setting) }}" method="POST" class="hidden" id="editForm-{{ $setting->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="setting_key" value="{{ $setting->setting_key }}">
                                <input type="hidden" name="group_name" value="{{ $setting->group_name }}">
                                <input type="hidden" name="type" value="{{ $setting->type }}">
                                <textarea name="setting_value" id="editValue-{{ $setting->id }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
                                <input type="hidden" name="is_public" value="{{ $setting->is_public ? 1 : 0 }}">
                                <input type="hidden" name="is_active" value="{{ $setting->is_active ? 1 : 0 }}">
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                <p>No settings found. Click "New Setting" to create one.</p>
            </div>
            @endforelse
        </div>
    </div>

    <script>
        function toggleCreateForm() {
            const form = document.getElementById('createForm');
            form.classList.toggle('hidden');
        }

        function editSetting(settingId) {
            // Simple inline edit - in production, use a modal
            alert('Edit functionality to be implemented');
        }
    </script>
</x-app-layout>
