<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit TODA') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('toda.update', $toda) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- TODA Name -->
                            <div>
                                <label for="toda_name" class="block text-sm font-medium text-gray-700">TODA Name</label>
                                <input type="text" 
                                       name="toda_name" 
                                       id="toda_name" 
                                       value="{{ old('toda_name', $toda->name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>

                            <!-- TODA President -->
                            <div>
                                <label for="toda_president" class="block text-sm font-medium text-gray-700">President Name</label>
                                <input type="text" 
                                       name="toda_president" 
                                       id="toda_president" 
                                       value="{{ old('toda_president', $toda->president) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>

                            <!-- Registration Date -->
                            <div>
                                <label for="registration_date" class="block text-sm font-medium text-gray-700">Registration Date</label>
                                <input type="date" 
                                       name="registration_date" 
                                       id="registration_date" 
                                       value="{{ old('registration_date', $toda->registration_date->format('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" 
                                        id="status" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required>
                                    <option value="active" {{ old('status', $toda->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $toda->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $toda->description) }}</textarea>
                        </div>

                        <!-- Warning for Inactive Status -->
                        <div id="inactiveWarning" class="hidden bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 my-4">
                            <p>Warning: Setting the TODA to inactive will also mark all associated operators as inactive.</p>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('toda.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update TODA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const warningDiv = document.getElementById('inactiveWarning');

            function toggleWarning() {
                if (statusSelect.value === 'inactive') {
                    warningDiv.classList.remove('hidden');
                } else {
                    warningDiv.classList.add('hidden');
                }
            }

            // Check initial state
            toggleWarning();

            // Add event listener for changes
            statusSelect.addEventListener('change', toggleWarning);
        });
    </script>
    @endpush
</x-app-layout>