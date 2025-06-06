<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cancel Franchise') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Operator Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p><strong>Name:</strong> {{ $operator->full_name }}</p>
                                <p><strong>Address:</strong> {{ $operator->address }}</p>
                                <p><strong>TODA:</strong> {{ $operator->toda->name }}</p>
                            </div>
                            <div>
                                @if($operator->motorcycles->isNotEmpty())
                                    <?php $motorcycle = $operator->motorcycles->first(); ?>
                                    <p><strong>MTOP No:</strong> {{ $motorcycle->mtop_no }}</p>
                                    <p><strong>Plate No:</strong> {{ $motorcycle->plate_no }}</p>
                                    <p><strong>Make/Model:</strong> {{ $motorcycle->make }} ({{ $motorcycle->year_model }})</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('franchise-cancellations.store', $operator) }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label for="or_number" class="block text-sm font-medium text-gray-700">O.R. Number</label>
                                <input type="text" name="or_number" id="or_number" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('or_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₱</span>
                                    </div>
                                    <input type="number" name="amount" id="amount" required step="0.01" min="0"
                                        class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                @error('amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cancellation_date" class="block text-sm font-medium text-gray-700">Cancellation Date</label>
                                <input type="date" name="cancellation_date" id="cancellation_date" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ now()->format('Y-m-d') }}">
                                @error('cancellation_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="reason" class="block text-sm font-medium text-gray-700">Reason (Optional)</label>
                                <textarea name="reason" id="reason" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                @error('reason')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('operators.show', $operator) }}"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                Cancel
                            </a>
                            <button type="submit"
                                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                                Cancel Franchise
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 