<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('TODA Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">TODA List</h3>
                        <a href="{{ route('toda.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New TODA
                        </a>
                    </div>

                    <!-- Enhanced Search and Filter -->
                    <div class="mb-4 bg-gray-50 p-4 rounded-lg">
                        <form action="{{ route('toda.index') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                                    <input type="text" name="search" value="{{ request('search') }}" 
                                        class="mt-1 w-full rounded-md border-gray-300" 
                                        placeholder="Search TODA name or president...">
                                </div>
                                
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Filter by Status</label>
                                    <select name="status" 
                                            id="status" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            onchange="this.form.submit()">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                                        class="mt-1 w-full rounded-md border-gray-300">
                                </div>

                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                                        class="mt-1 w-full rounded-md border-gray-300">
                                </div>
                            </div>

                            <div class="flex justify-end space-x-4">
                                <a href="{{ route('toda.index') }}" 
                                   class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                    Clear Filters
                                </a>
                                <button type="submit" 
                                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                    Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Enhanced TODA Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ route('toda.index', array_merge(request()->query(), [
                                            'sort' => 'name',
                                            'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc'
                                        ])) }}" class="flex items-center">
                                            TODA Name
                                            @if(request('sort') === 'name')
                                                <span class="ml-1">
                                                    @if(request('direction') === 'asc')
                                                        ↑
                                                    @else
                                                        ↓
                                                    @endif
                                                </span>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        President
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Registration Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Members
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($todas as $toda)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $toda->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $toda->president }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $toda->registration_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $toda->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($toda->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $toda->operators_count }} members
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('toda.edit', $toda) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    Edit
                                                </a>

                                                <button type="button" 
                                                        data-toda-toggle="true"
                                                        data-toda-id="{{ $toda->id }}"
                                                        data-toda-action="{{ $toda->status === 'active' ? 'deactivate' : 'activate' }}"
                                                        data-operator-count="{{ $toda->operators_count }}"
                                                        class="{{ $toda->status === 'active' ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}">
                                                    {{ $toda->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No TODAs found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Enhanced Pagination -->
                    <div class="mt-4">
                        {{ $todas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deactivation Modal -->
    <div id="deactivationModal" class="fixed inset-0 z-50 hidden">
        <!-- Modal Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Modal Content -->
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div id="modalIcon" class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 id="modalTitle" class="text-base font-semibold leading-6 text-gray-900">Change TODA Status</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        This TODA has <span id="operatorCount" class="font-semibold"></span> active operators.
                                        <span id="modalMessage"></span>
                                    </p>
                                    <div id="deactivateWarning" class="mt-4">
                                        <ul class="list-disc list-inside text-sm text-gray-600">
                                            <li>TODA will be marked as inactive</li>
                                            <li>All operators under this TODA will be marked as inactive</li>
                                            <li>Operators will need to be reassigned to an active TODA to resume operations</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form id="statusForm" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" id="confirmButton" class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:w-auto">
                                Confirm
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Function to show modal
        function showModal(todaId, action, operatorCount) {
            const modal = document.getElementById('deactivationModal');
            const form = document.getElementById('statusForm');
            const operatorCountSpan = document.getElementById('operatorCount');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalIcon = document.getElementById('modalIcon');
            const confirmButton = document.getElementById('confirmButton');
            const deactivateWarning = document.getElementById('deactivateWarning');
            
            // Update form action
            form.action = `{{ url('admin/toda') }}/${todaId}/toggle-status`;
            
            // Update operator count
            operatorCountSpan.textContent = operatorCount;
            
            // Update modal content based on action
            if (action === 'deactivate') {
                modalTitle.textContent = 'Deactivate TODA';
                modalMessage.textContent = 'Deactivating this TODA will also mark all associated operators as inactive.';
                modalIcon.className = 'mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10';
                modalIcon.querySelector('svg').className = 'h-6 w-6 text-red-600';
                confirmButton.className = 'inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto';
                deactivateWarning.style.display = 'block';
            } else {
                modalTitle.textContent = 'Activate TODA';
                modalMessage.textContent = 'Activating this TODA will allow it to resume operations.';
                modalIcon.className = 'mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10';
                modalIcon.querySelector('svg').className = 'h-6 w-6 text-green-600';
                confirmButton.className = 'inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:w-auto';
                deactivateWarning.style.display = 'none';
            }
            
            // Show modal
            modal.classList.remove('hidden');
        }

        // Function to close modal
        function closeModal() {
            const modal = document.getElementById('deactivationModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // Add event listeners when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Setup toggle buttons
            document.querySelectorAll('[data-toda-toggle]').forEach(button => {
                button.addEventListener('click', function() {
                    const todaId = this.dataset.todaId;
                    const action = this.dataset.todaAction;
                    const operatorCount = this.dataset.operatorCount;
                    showModal(todaId, action, operatorCount);
                });
            });

            // Close modal when clicking outside
            const modal = document.getElementById('deactivationModal');
            const backdrop = modal.querySelector('.bg-gray-500');
            const closeButton = modal.querySelector('button[type="button"]');

            // Handle backdrop click
            backdrop.addEventListener('click', closeModal);

            // Handle close button click
            closeButton.addEventListener('click', closeModal);

            // Handle ESC key press
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 