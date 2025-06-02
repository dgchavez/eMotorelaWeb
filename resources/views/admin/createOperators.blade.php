<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New MTOP Application') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('operators.store') }}">
                        @csrf

                        <!-- Operator Details -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-lg mb-4">Name of Operator</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="last_name" :value="__('Last Name')" />
                                    <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="first_name" :value="__('First Name')" />
                                    <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="middle_name" :value="__('Middle Name')" />
                                    <x-text-input id="middle_name" name="middle_name" type="text" class="mt-1 block w-full" />
                                    <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-input-label for="address" :value="__('Operator\'s Address')" />
                                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <x-input-label for="contact_no" :value="__('Contact No.')" />
                                    <x-text-input id="contact_no" name="contact_no" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('contact_no')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="email" :value="__('Email Address')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Motorcycle Unit Detail -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-lg mb-4">Motorcycle Unit Detail</h3>
                            
                            <div class="mb-4">
                                <x-input-label for="mtop_no" :value="__('MTOP #')" />
                                <x-text-input id="mtop_no" name="mtop_no" type="text" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('mtop_no')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <x-input-label for="motor_no" :value="__('Motor #')" />
                                    <x-text-input id="motor_no" name="motor_no" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('motor_no')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="chassis_no" :value="__('Chassis #')" />
                                    <x-text-input id="chassis_no" name="chassis_no" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('chassis_no')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="make" :value="__('Make')" />
                                    <x-text-input id="make" name="make" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('make')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="year_model" :value="__('Year Model')" />
                                    <x-text-input id="year_model" name="year_model" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('year_model')" class="mt-2" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                <div>
                                    <x-input-label for="mv_file_no" :value="__('MV File #')" />
                                    <x-text-input id="mv_file_no" name="mv_file_no" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('mv_file_no')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="plate_no" :value="__('Plate #')" />
                                    <x-text-input id="plate_no" name="plate_no" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('plate_no')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="color" :value="__('Color')" />
                                    <x-text-input id="color" name="color" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('color')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- TODA Information -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-lg mb-4">TODA Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="toda_id" :value="__('TODA Name')" />
                                    <select id="toda_id" name="toda_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>
                                        <option value="">Select TODA</option>
                                        @foreach($todas as $toda)
                                            <option value="{{ $toda->id }}">{{ $toda->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('toda_id')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="registration_date" :value="__('Registration Date')" />
                                    <x-text-input id="registration_date" name="registration_date" type="date" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('registration_date')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="mb-6">
                            <h3 class="font-semibold text-lg mb-4">In Case of Emergency</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="emergency_contact_person" :value="__('Contact Person')" />
                                    <x-text-input id="emergency_contact_person" name="emergency_contact_person" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('emergency_contact_person')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="emergency_contact_number" :value="__('Tel.No./CP no.')" />
                                    <x-text-input id="emergency_contact_number" name="emergency_contact_number" type="text" class="mt-1 block w-full" required />
                                    <x-input-error :messages="$errors->get('emergency_contact_number')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button type="button" class="mr-3" onclick="window.location='{{ route('operators.index') }}'">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Create MTOP') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>