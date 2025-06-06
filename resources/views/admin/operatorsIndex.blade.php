<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Operator Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Operators List</h3>
                        <a href="{{ route('operators.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Operator
                        </a>
                    </div>

                    <!-- Search and Filter -->
                    <div class="mb-4">
                        <form action="{{ route('operators.index') }}" method="GET" class="flex gap-4">
                            <div class="flex-1">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="w-full rounded-md border-gray-300" 
                                    placeholder="Search name, MTOP #, or plate #...">
                            </div>
                            <div class="w-48">
                                <select name="toda_id" class="w-full rounded-md border-gray-300">
                                    <option value="">All TODAs</option>
                                    @foreach($todas as $toda)
                                        <option value="{{ $toda->id }}" {{ request('toda_id') == $toda->id ? 'selected' : '' }}>
                                            {{ $toda->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                Search
                            </button>
                            <a href="{{ route('operators.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                Clear
                            </a>
                        </form>
                    </div>

                    <!-- Operators Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Operator Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        MTOP Details
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        TODA
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contact
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($operators as $operator)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $operator->last_name }}, {{ $operator->first_name }} {{ $operator->middle_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $operator->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($operator->status) }}
                                                </span>
                                                @if($operator->status === 'inactive' && $operator->deactivation_reason)
                                                    <span class="text-xs text-gray-500 ml-2">
                                                        ({{ $operator->deactivation_reason }})
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                @if($operator->motorcycles->first())
                                                    MTOP #: {{ $operator->motorcycles->first()->mtop_no }}
                                                    <br>
                                                    Plate #: {{ $operator->motorcycles->first()->plate_no }}
                                                @else
                                                    No motorcycle registered
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $operator->toda ? $operator->toda->name : 'Not assigned' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $operator->contact_no }}
                                            </div>
                                            @if($operator->email)
                                                <div class="text-sm text-gray-500">
                                                    {{ $operator->email }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $operator->status }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <!-- View -->
                                                <button type="button"
                                                        onclick="showOperatorDetails({{ $operator->id }})"
                                                        class="text-blue-600 hover:text-blue-900"
                                                        title="View Details">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>

                                                <!-- Edit -->
                                                <a href="{{ route('operators.edit', $operator) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900"
                                                   title="Edit Operator">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>

                                                <!-- Delete -->
                                                <button type="button" 
                                                        onclick="confirmDelete({{ $operator->id }}, '{{ $operator->full_name }}')"
                                                        class="text-red-600 hover:text-red-900" 
                                                        title="Delete Operator">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>

                                                <!-- Hidden Delete Form -->
                                                <form id="delete-form-{{ $operator->id }}" 
                                                      action="{{ route('operators.destroy', $operator) }}" 
                                                      method="POST" 
                                                      class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No operators found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $operators->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Preview Modal -->
    <div id="previewModal" 
         x-data="{ show: false }" 
         x-show="show" 
         @preview-modal.window="show = true" 
         @close-preview-modal.window="show = false"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="previewModalTitle">
                                Document Preview
                            </h3>
                            <div class="mt-4">
                                <div id="previewModalContent" class="border rounded-lg p-4 min-h-[600px] overflow-y-auto">
                                    <!-- Preview content will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        type="button" 
                        id="generateBtn"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Generate Document
                    </button>
                    <button 
                        type="button" 
                        @click="show = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Operator Details Modal -->
    <div id="operatorDetailsModal" 
         x-data="{ show: false }" 
         x-show="show" 
         @operator-modal.window="show = true" 
         @close-operator-modal.window="show = false"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div id="operatorDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="show = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('head-scripts')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    @push('scripts')
    <script>
    let currentDocumentType = null;
    let currentOperatorId = null;

    async function previewDocument(type, operatorId, operatorName) {
        try {
            Swal.fire({
                title: 'Loading Preview...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch(`/documents/preview/${type}/${operatorId}`);
            const html = await response.text();

            if (!response.ok) {
                throw new Error('Failed to load preview');
            }

            Swal.fire({
                title: `${type.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')} - ${operatorName}`,
                html: html,
                width: '80%',
                showCancelButton: true,
                confirmButtonText: 'Generate Document',
                cancelButtonText: 'Close',
                customClass: {
                    container: 'preview-modal-container',
                    popup: 'preview-modal-popup',
                    content: 'preview-modal-content'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    generateDocument(type, operatorId);
                }
            });
        } catch (error) {
            console.error('Preview error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load document preview'
            });
        }
    }

    async function generateDocument(type, operatorId) {
        try {
            Swal.fire({
                title: 'Generating Document...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch(`/documents/${type}/${operatorId}`);
            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Failed to generate document');
            }

            // Open the document in a new tab
            window.open(data.url, '_blank');

            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message
            });
        } catch (error) {
            console.error('Generation error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Failed to generate document'
            });
        }
    }

    function confirmDelete(operatorId, operatorName) {
        Swal.fire({
            title: 'Delete Operator',
            html: `Are you sure you want to delete <strong>${operatorName}</strong>?<br>This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${operatorId}`).submit();
            }
        });
    }

    async function showOperatorDetails(operatorId) {
        try {
            Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const response = await fetch(`/admin/operators/${operatorId}`);
            const data = await response.json();

            if (!response.ok) {
                throw new Error('Failed to load operator details');
            }

            Swal.fire({
                title: 'Operator Details',
                html: data.html,
                width: '80%',
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    container: 'operator-modal-container',
                    popup: 'operator-modal-popup',
                    content: 'operator-modal-content'
                }
            });
        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load operator details'
            });
        }
    }
    </script>
    @endpush

    <style>
    [x-cloak] { 
        display: none !important; 
    }

    .origin-top-right {
        transform-origin: top right;
    }

    .transition {
        transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }

    .ease-out {
        transition-timing-function: cubic-bezier(0, 0, 0.2, 1);
    }

    .ease-in {
        transition-timing-function: cubic-bezier(0.4, 0, 1, 1);
    }

    .duration-100 {
        transition-duration: 100ms;
    }

    .duration-75 {
        transition-duration: 75ms;
    }

    .transform {
        --transform-translate-x: 0;
        --transform-translate-y: 0;
        --transform-rotate: 0;
        --transform-skew-x: 0;
        --transform-skew-y: 0;
        --transform-scale-x: 1;
        --transform-scale-y: 1;
        transform: translateX(var(--transform-translate-x)) translateY(var(--transform-translate-y)) rotate(var(--transform-rotate)) skewX(var(--transform-skew-x)) skewY(var(--transform-skew-y)) scaleX(var(--transform-scale-x)) scaleY(var(--transform-scale-y));
    }

    .scale-95 {
        --transform-scale-x: .95;
        --transform-scale-y: .95;
    }

    .scale-100 {
        --transform-scale-x: 1;
        --transform-scale-y: 1;
    }
    </style>
</x-app-layout> 