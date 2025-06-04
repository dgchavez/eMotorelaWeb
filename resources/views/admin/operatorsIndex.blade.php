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
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('operators.show', $operator) }}" class="text-blue-600 hover:text-blue-900">
                                                    View
                                                </a>
                                                <a href="{{ route('operators.edit', $operator) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    Edit
                                                </a>
                                                
                                                <!-- Document Generation Dropdown -->
                                                <div x-data="{ open: false }" class="relative inline-block text-left">
                                                    <button @click="open = !open" 
                                                            type="button" 
                                                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        Documents
                                                        <svg class="w-4 h-4 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>

                                                    <div x-show="open" 
                                                         @click.away="open = false"
                                                         x-transition:enter="transition ease-out duration-100"
                                                         x-transition:enter-start="transform opacity-0 scale-95"
                                                         x-transition:enter-end="transform opacity-100 scale-100"
                                                         x-transition:leave="transition ease-in duration-75"
                                                         x-transition:leave-start="transform opacity-100 scale-100"
                                                         x-transition:leave-end="transform opacity-0 scale-95"
                                                         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50"
                                                         style="display: none;">
                                                        <div class="py-1">
                                                            <button @click="open = false; previewDocument('franchise-certificate', '{{ $operator->id }}', '{{ $operator->full_name }}')" 
                                                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 w-full text-left">
                                                                Franchise Certificate
                                                            </button>
                                                            <button @click="open = false; previewDocument('motorela-permit', '{{ $operator->id }}', '{{ $operator->full_name }}')" 
                                                                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 w-full text-left">
                                                                Motorela Permit
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <form action="{{ route('operators.destroy', $operator) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                                        onclick="return confirm('Are you sure you want to delete this operator?')">
                                                        Delete
                                                    </button>
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