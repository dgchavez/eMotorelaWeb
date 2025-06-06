                            <div class="flex justify-end mt-4 space-x-4">
                                <a href="{{ route('operators.edit', $operator) }}" 
                                   class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                    Edit Operator
                                </a>
                                @if($operator->status === 'active')
                                    <a href="{{ route('franchise-cancellations.create', $operator) }}"
                                       class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600"
                                       onclick="return confirm('Are you sure you want to cancel this franchise? This action cannot be undone.')">
                                        Cancel Franchise
                                    </a>
                                @endif
                            </div> 