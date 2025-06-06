<div class="p-6">
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Operator Details</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="font-medium text-gray-900 mb-4">Personal Information</h4>
            <dl class="grid grid-cols-1 gap-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                    <dd class="text-sm text-gray-900">{{ $operator->last_name }}, {{ $operator->first_name }} {{ $operator->middle_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Contact Number</dt>
                    <dd class="text-sm text-gray-900">{{ $operator->contact_no }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900">{{ $operator->email ?: 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="text-sm text-gray-900">{{ $operator->address }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $operator->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($operator->status) }}
                        </span>
                        @if($operator->status === 'inactive' && $operator->deactivation_reason)
                            <span class="text-xs text-gray-500 ml-2">({{ $operator->deactivation_reason }})</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <!-- TODA & Vehicle Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h4 class="font-medium text-gray-900 mb-4">TODA & Vehicle Information</h4>
            <dl class="grid grid-cols-1 gap-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">TODA</dt>
                    <dd class="text-sm text-gray-900">{{ $operator->toda ? $operator->toda->name : 'Not assigned' }}</dd>
                </div>
                @if($operator->motorcycles->first())
                    <div>
                        <dt class="text-sm font-medium text-gray-500">MTOP Number</dt>
                        <dd class="text-sm text-gray-900">{{ $operator->motorcycles->first()->mtop_no }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Plate Number</dt>
                        <dd class="text-sm text-gray-900">{{ $operator->motorcycles->first()->plate_no }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Make/Model</dt>
                        <dd class="text-sm text-gray-900">{{ $operator->motorcycles->first()->make }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Motor Number</dt>
                        <dd class="text-sm text-gray-900">{{ $operator->motorcycles->first()->motor_no }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Chassis Number</dt>
                        <dd class="text-sm text-gray-900">{{ $operator->motorcycles->first()->chassis_no }}</dd>
                    </div>
                @else
                    <div>
                        <dd class="text-sm text-gray-500">No motorcycle registered</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Actions Footer -->
    <div class="mt-6 bg-gray-50 -mx-6 -mb-6 px-6 py-3 flex justify-end space-x-3">
        <!-- Documents Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <div x-show="open" 
                 @click.away="open = false"
                 class="absolute bottom-full right-0 mb-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50">
                <div class="py-1">
                    <button @click="open = false; previewDocument('franchise-certificate', '{{ $operator->id }}', '{{ $operator->full_name }}')" 
                            class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 w-full text-left">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Franchise Certificate
                    </button>
                    <button @click="open = false; previewDocument('motorela-permit', '{{ $operator->id }}', '{{ $operator->full_name }}')" 
                            class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 w-full text-left">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                        Motorela Permit
                    </button>
                </div>
            </div>

            <button @click="open = !open" 
                    type="button" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Documents
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
            </button>
        </div>

        @if($operator->status === 'active')
            <!-- Cancel Franchise -->
            <a href="{{ route('franchise-cancellations.create', $operator) }}"
               onclick="return confirm('Are you sure you want to cancel this franchise? This action cannot be undone.')"
               class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Cancel Franchise
            </a>
        @endif

        <!-- Close -->
        <button type="button" 
                @click="show = false"
                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Close
        </button>
    </div>
</div> 