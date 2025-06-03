<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Motorized Tricycle Operator\'s Permit (MTOP)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('operators.store') }}" method="POST">
                        @csrf
                        
                        @if($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">
                                            There were errors with your submission
                                        </h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                @if($errors->has('operator_error'))
                                                    <li>{{ $errors->first('operator_error') }}</li>
                                                @endif
                                                @if($errors->has('motorcycle_error'))
                                                    <li>{{ $errors->first('motorcycle_error') }}</li>
                                                @endif
                                                @if($errors->has('emergency_contact_error'))
                                                    <li>{{ $errors->first('emergency_contact_error') }}</li>
                                                @endif
                                                @if($errors->has('database_error'))
                                                    <li>{{ $errors->first('database_error') }}</li>
                                                @endif
                                                @if($errors->has('error'))
                                                    <li>{{ $errors->first('error') }}</li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Operator Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 bg-gray-800 text-white px-4 py-2">Name of Operator</h3>
                            <div class="grid grid-cols-3 gap-4">
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
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="address" class="block text-sm font-medium text-gray-700">Operator's Address</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact No.</label>
                                    <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('contact_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Motorcycle Unit Detail -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 bg-gray-800 text-white px-4 py-2">Motorcycle Unit Detail</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="mtop_no" class="block text-sm font-medium text-gray-700">MTOP #</label>
                                    <input type="text" name="mtop_no" id="mtop_no" value="{{ old('mtop_no') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('mtop_no')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-4 gap-4 mt-4">
                                <div>
                                    <label for="motor_no" class="block text-sm font-medium text-gray-700">Motor #</label>
                                    <input type="text" name="motor_no" id="motor_no" value="{{ old('motor_no') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="chassis_no" class="block text-sm font-medium text-gray-700">Chassis #</label>
                                    <input type="text" name="chassis_no" id="chassis_no" value="{{ old('chassis_no') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="make" class="block text-sm font-medium text-gray-700">Make</label>
                                    <input type="text" name="make" id="make" value="{{ old('make') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="year_model" class="block text-sm font-medium text-gray-700">Year Model</label>
                                    <input type="text" name="year_model" id="year_model" value="{{ old('year_model') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label for="mv_file_no" class="block text-sm font-medium text-gray-700">MV File #</label>
                                    <input type="text" name="mv_file_no" id="mv_file_no" value="{{ old('mv_file_no') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="plate_no" class="block text-sm font-medium text-gray-700">Plate #</label>
                                    <input type="text" name="plate_no" id="plate_no" value="{{ old('plate_no') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('plate_no')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                                    <input type="text" name="color" id="color" value="{{ old('color') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- TODA Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 bg-gray-800 text-white px-4 py-2">Tricycle Operator Drivers Association (TODA)</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="toda_id" class="block text-sm font-medium text-gray-700">TODA Name</label>
                                    <select name="toda_id" id="toda_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select TODA</option>
                                        @foreach($todas as $toda)
                                            <option value="{{ $toda->id }}" {{ old('toda_id') == $toda->id ? 'selected' : '' }}>
                                                {{ $toda->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('toda_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="registration_date" class="block text-sm font-medium text-gray-700">Registration Date</label>
                                    <input type="date" name="registration_date" id="registration_date" value="{{ old('registration_date') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 bg-gray-800 text-white px-4 py-2">In Case of Emergency</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Contact Person</label>
                                    <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="emergency_contact_no" class="block text-sm font-medium text-gray-700">Tel.No./CP no.</label>
                                    <input type="text" name="emergency_contact_no" id="emergency_contact_no" value="{{ old('emergency_contact_no') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="{{ route('operators.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create MTOP
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 