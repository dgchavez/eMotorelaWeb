@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        console.log('jQuery loaded and document ready');
        
        // Event handler for opening modal
        $('#openDriverModalButton').on('click', function(e) {
            e.preventDefault();
            console.log('Open button clicked');
            $('#driverModal').removeClass('hidden');
            $('body').addClass('overflow-hidden');
        });

        // Event handlers for closing modal
        $('#closeDriverModalButton, #cancelDriverFormButton').on('click', function(e) {
            e.preventDefault();
            console.log('Close button clicked');
            $('#driverModal').addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $('#driverForm')[0].reset();
        });

        // Close modal when clicking outside
        $('#driverModal').on('click', function(e) {
            if (e.target === this) {
                $('#driverModal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
            }
        });

        // Initialize drivers array with existing data
        let drivers = @json($driversArray);

        $('#driverForm').on('submit', function(e) {
            e.preventDefault();
            if (validateForm()) {
                const formData = new FormData(this);
                const newDriver = {};
                formData.forEach((value, key) => {
                    newDriver[key] = value;
                });

                drivers.push(newDriver);
                renderDriversList();
                updateMainForm();
                
                $('#driverModal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
                this.reset();
            }
        });

        $('#addAnotherDriverButton').on('click', function(e) {
            e.preventDefault();
            if (validateForm()) {
                const formData = new FormData($('#driverForm')[0]);
                const newDriver = {};
                formData.forEach((value, key) => {
                    newDriver[key] = value;
                });

                drivers.push(newDriver);
                renderDriversList();
                updateMainForm();
                $('#driverForm')[0].reset();
                showSuccessMessage();
            }
        });

        // Your existing functions converted to jQuery
        function validateForm() {
            removeValidationErrors();
            let isValid = true;

            $('#driverForm [required]').each(function() {
                if (!$(this).val().trim()) {
                    isValid = false;
                    $(this).addClass('border-red-500');
                    $('<div class="validation-error text-red-500 text-sm mt-1">This field is required</div>')
                        .insertAfter(this);
                }
            });

            // Add your other validation logic here

            return isValid;
        }

        function removeValidationErrors() {
            $('.validation-error').remove();
            $('.border-red-500').removeClass('border-red-500');
        }

        function showSuccessMessage() {
            const successMessage = `
                <div class="success-message bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">Driver added successfully! You can add another driver.</p>
                        </div>
                    </div>
                </div>
            `;
            
            $('.success-message').remove();
            $(successMessage).insertBefore('#driverForm');
            setTimeout(() => $('.success-message').fadeOut('slow', function() { $(this).remove(); }), 3000);
        }

        function updateMainForm() {
            $('#driversDataForForm').empty();
            drivers.forEach((driver, index) => {
                Object.keys(driver).forEach(key => {
                    $('<input>')
                        .attr({
                            type: 'hidden',
                            name: `drivers[${index}][${key}]`,
                            value: driver[key]
                        })
                        .appendTo('#driversDataForForm');
                });
            });
        }

        function renderDriversList() {
            const $content = $('#driversListContent');
            $content.empty();
            
            if (drivers.length === 0) {
                $('#noDriversMessage').show();
            } else {
                $('#noDriversMessage').hide();
                
                // Add count header
                $content.append(`
                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Total Drivers: ${drivers.length}</span>
                        </div>
                    </div>
                `);

                // Add driver items
                drivers.forEach((driver, index) => {
                    const driverItem = `
                        <div class="p-4 flex justify-between items-start hover:bg-gray-50 border-b border-gray-200">
                            <div class="flex-grow">
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-gray-900">${driver.last_name}, ${driver.first_name} ${driver.middle_name || ''}</p>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Driver ${index + 1}
                                    </span>
                                </div>
                                <div class="mt-1 text-sm text-gray-500 space-y-1">
                                    <p>License: ${driver.drivers_license_no}</p>
                                    <p>Expires: ${new Date(driver.license_expiry_date).toLocaleDateString()}</p>
                                    <p>Contact: ${driver.contact_no}</p>
                                    <p class="text-xs text-gray-400">${driver.address}</p>
                                </div>
                            </div>
                            <button type="button" class="remove-driver-button ml-4 text-red-500 hover:text-red-700 font-medium flex items-center" data-index="${index}">
                                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Remove
                            </button>
                        </div>
                    `;
                    $content.append(driverItem);
                });

                // Add remove button handlers
                $('.remove-driver-button').on('click', function() {
                    const index = $(this).data('index');
                    if (confirm('Are you sure you want to remove this driver?')) {
                        drivers.splice(index, 1);
                        renderDriversList();
                        updateMainForm();
                    }
                });
            }
        }

        // Initial render
        renderDriversList();

        // Operator is also driver logic
        function getOperatorDriver() {
            return {
                last_name: $('#last_name').val(),
                first_name: $('#first_name').val(),
                middle_name: $('#middle_name').val(),
                address: $('#address').val(),
                contact_no: $('#contact_number').val(),
                drivers_license_no: $('#operator_license_no').val(),
                license_expiry_date: $('#operator_license_expiry').val()
            };
        }
        function isOperatorDriverPresent() {
            return drivers.length > 0 && drivers[0]._isOperator;
        }
        function updateOperatorDriverInList() {
            const opDriver = getOperatorDriver();
            opDriver._isOperator = true;
            if (isOperatorDriverPresent()) {
                drivers[0] = opDriver;
            } else {
                drivers.unshift(opDriver);
            }
        }
        function removeOperatorDriverFromList() {
            if (isOperatorDriverPresent()) {
                drivers.shift();
            }
        }
        $('#operatorIsDriver').on('change', function() {
            if ($(this).is(':checked')) {
                $('#operator-license-fields').show();
                updateOperatorDriverInList();
                renderDriversList();
                updateMainForm();
            } else {
                $('#operator-license-fields').hide();
                removeOperatorDriverFromList();
                renderDriversList();
                updateMainForm();
            }
        });
        // Update operator driver details when operator fields change
        $('#last_name, #first_name, #middle_name, #address, #contact_number').on('change', function() {
            if ($('#operatorIsDriver').is(':checked')) {
                updateOperatorDriverInList();
                renderDriversList();
                updateMainForm();
            }
        });

        // When the license fields change, update the operator driver entry
        $('#operator_license_no, #operator_license_expiry').on('input change', function() {
            if ($('#operatorIsDriver').is(':checked')) {
                if (drivers.length > 0 && drivers[0]._isOperator) {
                    drivers[0].drivers_license_no = $('#operator_license_no').val();
                    drivers[0].license_expiry_date = $('#operator_license_expiry').val();
                    renderDriversList();
                    updateMainForm();
                }
            }
        });

        $('#existing_driver_select').on('change', function() {
            const selected = $(this).find('option:selected');
            if (selected.val()) {
                $('#driver_last_name').val(selected.data('last_name'));
                $('#driver_first_name').val(selected.data('first_name'));
                $('#driver_middle_name').val(selected.data('middle_name'));
                $('#driver_address').val(selected.data('address'));
                $('#driver_contact_no').val(selected.data('contact_no'));
                $('#driver_license_no').val(selected.data('drivers_license_no'));
                $('#driver_license_expiry_date').val(selected.data('license_expiry_date'));
            } else {
                // Clear fields for new driver
                $('#driver_last_name, #driver_first_name, #driver_middle_name, #driver_address, #driver_contact_no, #driver_license_no, #driver_license_expiry_date').val('');
            }
        });
    });
</script>
@endpush

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
                    <form action="{{ route('operators.update', $operator->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
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
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
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
                                    <input type="text" name="last_name" id="last_name" 
                                        value="{{ old('last_name', $operator->last_name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" name="first_name" id="first_name" 
                                        value="{{ old('first_name', $operator->first_name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" 
                                        value="{{ old('middle_name', $operator->middle_name) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="address" class="block text-sm font-medium text-gray-700">Operator's Address</label>
                                <input type="text" name="address" id="address" 
                                    value="{{ old('address', $operator->address) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact No.</label>
                                    <input type="text" name="contact_number" id="contact_number" 
                                        value="{{ old('contact_number', $operator->contact_no) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                    <input type="email" name="email" id="email" 
                                        value="{{ old('email', $operator->email) }}"
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
                                            <option value="{{ $toda->id }}" 
                                                {{ old('toda_id', $operator->toda_id) == $toda->id ? 'selected' : '' }}>
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
                                    <input type="date" name="registration_date" id="registration_date" 
                                        value="{{ old('registration_date', $motorcycle->registration_date ? date('Y-m-d', strtotime($motorcycle->registration_date)) : '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('registration_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Motorcycle Unit Detail -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 bg-gray-800 text-white px-4 py-2">Motorcycle Unit Detail</h3>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="mtop_no" class="block text-sm font-medium text-gray-700">MTOP #</label>
                                    <input type="text" name="mtop_no" id="mtop_no" 
                                        value="{{ old('mtop_no', $motorcycle->mtop_no ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('mtop_no')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-4 gap-4 mt-4">
                                <div>
                                    <label for="motor_no" class="block text-sm font-medium text-gray-700">Motor #</label>
                                    <input type="text" name="motor_no" id="motor_no" 
                                        value="{{ old('motor_no', $motorcycle->motor_no ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('motor_no')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="chassis_no" class="block text-sm font-medium text-gray-700">Chassis #</label>
                                    <input type="text" name="chassis_no" id="chassis_no" 
                                        value="{{ old('chassis_no', $motorcycle->chassis_no ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('chassis_no')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="make" class="block text-sm font-medium text-gray-700">Make</label>
                                    <input type="text" name="make" id="make" 
                                        value="{{ old('make', $motorcycle->make ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('make')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="year_model" class="block text-sm font-medium text-gray-700">Year Model</label>
                                    <input type="text" name="year_model" id="year_model" 
                                        value="{{ old('year_model', $motorcycle->year_model ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('year_model')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mt-4">
                                <div>
                                    <label for="mv_file_no" class="block text-sm font-medium text-gray-700">MV File #</label>
                                    <input type="text" name="mv_file_no" id="mv_file_no" 
                                        value="{{ old('mv_file_no', $motorcycle->mv_file_no ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('mv_file_no')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="plate_no" class="block text-sm font-medium text-gray-700">Plate #</label>
                                    <input type="text" name="plate_no" id="plate_no" 
                                        value="{{ old('plate_no', $motorcycle->plate_no ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('plate_no')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                                    <input type="text" name="color" id="color" 
                                        value="{{ old('color', $motorcycle->color ?? '') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('color')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Driver's Details -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 bg-gray-800 text-white px-4 py-2">Driver's Details</h3>
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="operatorIsDriver" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mr-2">
                                        <label for="operatorIsDriver" class="text-sm font-medium text-gray-700">Operator is also the driver</label>
                                    </div>
                                    <h4 class="text-md font-medium text-gray-700">Drivers List</h4>
                                    <button type="button" id="openDriverModalButton" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Add Driver
                                    </button>
                                </div>

                                <div id="driversListContainer" class="bg-white rounded-lg border border-gray-200">
                                    <div class="p-4 text-gray-500 text-center" id="noDriversMessage">
                                        No drivers added yet.
                                    </div>
                                    <div class="divide-y divide-gray-200" id="driversListContent">
                                        <!-- Driver items will be appended here by JavaScript -->
                                    </div>
                                </div>
                                <div id="driversDataForForm">
                                    <!-- Hidden inputs for drivers will be appended here -->
                                </div>
                                <!-- Add validation error message for drivers -->
                                @error('drivers')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="operator-license-fields" class="flex gap-4 mt-2" style="display:none;">
                                <div>
                                    <label for="operator_license_no" class="block text-xs font-medium text-gray-700">Driver's License #</label>
                                    <input type="text" id="operator_license_no" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                                </div>
                                <div>
                                    <label for="operator_license_expiry" class="block text-xs font-medium text-gray-700">License Expiry Date</label>
                                    <input type="date" id="operator_license_expiry" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4 bg-gray-800 text-white px-4 py-2">In Case of Emergency</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="emergency_contact" class="block text-sm font-medium text-gray-700">Contact Person</label>
                                    <input type="text" name="emergency_contact" id="emergency_contact" 
                                        value="{{ old('emergency_contact', $emergencyContact->contact_person) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="emergency_contact_no" class="block text-sm font-medium text-gray-700">Tel.No./CP no.</label>
                                    <input type="text" name="emergency_contact_no" id="emergency_contact_no" 
                                        value="{{ old('emergency_contact_no', $emergencyContact->tel_no) }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="{{ route('operators.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update MTOP
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Driver Modal -->
    <div id="driverModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white p-6">
                        <div class="flex items-center justify-between border-b pb-3">
                            <h3 class="text-xl font-semibold text-gray-900">Add New Driver</h3>
                            <button type="button" id="closeDriverModalButton" 
                                class="rounded-md p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form id="driverForm" class="mt-4 space-y-4">
                            <div class="mb-2">
                                <label for="existing_driver_select" class="block text-xs font-medium text-gray-700">Select Existing Driver</label>
                                <select id="existing_driver_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">-- New Driver --</option>
                                    @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}"
                                            data-last_name="{{ $driver->last_name }}"
                                            data-first_name="{{ $driver->first_name }}"
                                            data-middle_name="{{ $driver->middle_name }}"
                                            data-address="{{ $driver->address }}"
                                            data-contact_no="{{ $driver->contact_no }}"
                                            data-drivers_license_no="{{ $driver->drivers_license_no }}"
                                            data-license_expiry_date="{{ $driver->license_expiry_date ? $driver->license_expiry_date->format('Y-m-d') : '' }}"
                                        >
                                            {{ $driver->last_name }}, {{ $driver->first_name }} ({{ $driver->drivers_license_no }}) - Units: {{ $driver->operators->count() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="driver_last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" id="driver_last_name" name="last_name" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="driver_first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" id="driver_first_name" name="first_name" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="driver_middle_name" class="block text-sm font-medium text-gray-700">Middle Name</label>
                                <input type="text" id="driver_middle_name" name="middle_name"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="driver_address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" id="driver_address" name="address" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="driver_contact_no" class="block text-sm font-medium text-gray-700">Contact No.</label>
                                <input type="text" id="driver_contact_no" name="contact_no" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="driver_license_no" class="block text-sm font-medium text-gray-700">Driver's License #</label>
                                <input type="text" id="driver_license_no" name="drivers_license_no" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="driver_license_expiry_date" class="block text-sm font-medium text-gray-700">License Expiry Date</label>
                                <input type="date" id="driver_license_expiry_date" name="license_expiry_date" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div class="mt-6 flex justify-end space-x-3 border-t pt-4">
                                <button type="button" id="cancelDriverFormButton"
                                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Cancel
                                </button>
                                <button type="button" id="addAnotherDriverButton"
                                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-blue-600 shadow-sm hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Add & Continue
                                </button>
                                <button type="submit"
                                    class="rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Add & Close
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 