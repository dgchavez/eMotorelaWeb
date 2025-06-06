<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Driver') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.drivers.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Personal Information -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('middle_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="suffix" class="block text-sm font-medium text-gray-700">Suffix</label>
                                    <input type="text" name="suffix" id="suffix" value="{{ old('suffix') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('suffix')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <textarea name="address" id="address" rows="3" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="contact_no" class="block text-sm font-medium text-gray-700">Contact Number</label>
                                    <input type="text" name="contact_no" id="contact_no" value="{{ old('contact_no') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('contact_no')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- License Information -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">License Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="drivers_license_no" class="block text-sm font-medium text-gray-700">Driver's License Number</label>
                                    <input type="text" name="drivers_license_no" id="drivers_license_no" value="{{ old('drivers_license_no') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('drivers_license_no')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="license_expiry_date" class="block text-sm font-medium text-gray-700">License Expiry Date</label>
                                    <input type="date" name="license_expiry_date" id="license_expiry_date" 
                                        value="{{ old('license_expiry_date') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('license_expiry_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Operator Selection -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Associated Operators</h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Select Operators</label>
                                        <div class="mt-2 space-y-2">
                                            @foreach($operators as $operator)
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="operator_ids[]" value="{{ $operator->id }}"
                                                        id="operator_{{ $operator->id }}"
                                                        {{ in_array($operator->id, old('operator_ids', [])) ? 'checked' : '' }}
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <label for="operator_{{ $operator->id }}" class="ml-2 block text-sm text-gray-900">
                                                        {{ $operator->last_name }}, {{ $operator->first_name }}
                                                        @if($operator->motorcycles->isNotEmpty())
                                                            <span class="text-gray-500">
                                                                ({{ $operator->motorcycles->first()->plate_no }})
                                                            </span>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('operator_ids')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.drivers.index') }}" 
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                Create Driver
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 