<x-guest-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-800">Application Status Tracking</h2>
                        <p class="text-gray-600">Tracking Code: {{ $application->tracking_code }}</p>
                    </div>

                    <!-- Application Details -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Application Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600">Operator Name</p>
                                <p class="font-medium">{{ $application->operator->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">TODA</p>
                                <p class="font-medium">{{ $application->operator->toda->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Application Date</p>
                                <p class="font-medium">{{ $application->application_date->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Current Status</p>
                                <p class="font-medium">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Timeline -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Status Timeline</h3>
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($statusHistory as $index => $history)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                        {{ $history['status'] === 'Approved' ? 'bg-green-500' : 
                                                           ($history['status'] === 'Rejected' ? 'bg-red-500' : 
                                                           'bg-blue-500') }}">
                                                        <!-- Icon -->
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm text-gray-500">Status changed to <span class="font-medium text-gray-900">{{ $history['status'] }}</span></p>
                                                        @if($history['notes'])
                                                            <p class="mt-1 text-sm text-gray-500">{{ $history['notes'] }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        <time datetime="{{ $history['timestamp'] }}">{{ $history['timestamp'] }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="text-center">
                        <div class="inline-block p-4 bg-white rounded-lg shadow-md">
                            <img src="{{ $application->getQRCodeUrl() }}" alt="QR Code" class="mx-auto">
                            <p class="mt-2 text-sm text-gray-600">Scan to check status</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout> 