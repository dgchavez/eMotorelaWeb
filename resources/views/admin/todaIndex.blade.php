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

                                                <form action="{{ route('toda.toggle-status', $toda) }}" 
                                                      method="POST" 
                                                      class="inline-block"
                                                      onsubmit="return confirm('Are you sure you want to {{ $toda->status === 'active' ? 'deactivate' : 'activate' }} this TODA?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" 
                                                            class="{{ $toda->status === 'active' 
                                                                ? 'text-red-600 hover:text-red-900' 
                                                                : 'text-green-600 hover:text-green-900' }}">
                                                        {{ $toda->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
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
</x-app-layout> 