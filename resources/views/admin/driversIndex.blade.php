<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Drivers Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Search and Filter Form -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('admin.drivers.index') }}" class="flex gap-4">
                            <div class="flex-1">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    placeholder="Search by name or license number..."
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="flex-1">
                                <select name="operator_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Operators</option>
                                    @foreach($operators as $operator)
                                        <option value="{{ $operator->id }}" {{ request('operator_id') == $operator->id ? 'selected' : '' }}>
                                            {{ $operator->last_name }}, {{ $operator->first_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                Search
                            </button>
                        </form>
                    </div>

                    <!-- Drivers Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Driver Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        License No.
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Operator
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        TODA
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contact
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        License Expiry
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($drivers as $driver)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $driver->last_name }}, {{ $driver->first_name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $driver->drivers_license_no }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                @foreach($driver->operators as $operator)
                                                    <div class="mb-1">
                                                        {{ $operator->last_name }}, {{ $operator->first_name }}
                                                        @if($operator->motorcycles->first())
                                                            <span class="text-gray-500">
                                                                ({{ $operator->motorcycles->first()->plate_no }})
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                @foreach($driver->operators as $operator)
                                                    <div class="mb-1">
                                                        {{ $operator->toda->name }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $driver->contact_no }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm {{ $driver->license_expiry_date->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                                {{ $driver->license_expiry_date->format('M d, Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button type="button" 
                                                onclick="showMotorcycles({{ $driver->id }})"
                                                class="text-indigo-600 hover:text-indigo-900">
                                                View Motorcycles
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No drivers found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $drivers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Motorcycles Modal -->
    <div id="motorcyclesModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Driver's Motorcycles
                            </h3>
                            <div class="mt-2">
                                <div id="driverInfo" class="mb-4">
                                    <p class="text-sm text-gray-500">Loading driver information...</p>
                                </div>
                                <div id="motorcyclesList" class="mt-4">
                                    <div class="flex justify-center">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeMotorcyclesModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showMotorcycles(driverId) {
            const modal = document.getElementById('motorcyclesModal');
            modal.classList.remove('hidden');
            
            // Show loading state
            document.getElementById('driverInfo').innerHTML = '<p class="text-sm text-gray-500">Loading driver information...</p>';
            document.getElementById('motorcyclesList').innerHTML = `
                <div class="flex justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
                </div>
            `;

            // Fetch motorcycles data
            fetch(`/admin/drivers/${driverId}/motorcycles`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update driver info
                        document.getElementById('driverInfo').innerHTML = `
                            <p class="text-sm font-medium text-gray-900">${data.data.driver.name}</p>
                            <p class="text-sm text-gray-500">License No: ${data.data.driver.license_no}</p>
                        `;

                        // Update motorcycles list
                        if (data.data.units.length > 0) {
                            const unitsHtml = data.data.units.map(item => {
                                const unit = item.unit;
                                const operator = item.operator;
                                return `
                                <div class="border rounded-lg p-4 mb-3">
                                    <div class="mb-3">
                                        <h4 class="text-sm font-medium text-gray-900">Operator: ${operator.name}</h4>
                                        <p class="text-sm text-gray-500">TODA: ${operator.toda}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <h5 class="text-sm font-medium text-gray-900 mb-2">Unit Details:</h5>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <p class="text-xs text-gray-500">Plate No.</p>
                                                <p class="text-sm font-medium">${unit.plate_no}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">MTOP No.</p>
                                                <p class="text-sm font-medium">${unit.mtop_no}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Make & Model</p>
                                                <p class="text-sm font-medium">${unit.make} (${unit.year_model})</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Color</p>
                                                <p class="text-sm font-medium">${unit.color}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Motor No.</p>
                                                <p class="text-sm font-medium">${unit.motor_no}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Chassis No.</p>
                                                <p class="text-sm font-medium">${unit.chassis_no}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">MV File No.</p>
                                                <p class="text-sm font-medium">${unit.mv_file_no}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Registration Date</p>
                                                <p class="text-sm font-medium">${unit.registration_date}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `}).join('');
                            document.getElementById('motorcyclesList').innerHTML = unitsHtml;
                        } else {
                            document.getElementById('motorcyclesList').innerHTML = `
                                <p class="text-sm text-gray-500 text-center">No units found for this driver.</p>
                            `;
                        }
                    } else {
                        throw new Error(data.message || 'Failed to fetch motorcycles data');
                    }
                })
                .catch(error => {
                    document.getElementById('driverInfo').innerHTML = `
                        <p class="text-sm text-red-600">Error loading driver information</p>
                    `;
                    document.getElementById('motorcyclesList').innerHTML = `
                        <p class="text-sm text-red-600 text-center">${error.message}</p>
                    `;
                });
        }

        function closeMotorcyclesModal() {
            const modal = document.getElementById('motorcyclesModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('motorcyclesModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMotorcyclesModal();
            }
        });
    </script>
    @endpush
</x-app-layout>