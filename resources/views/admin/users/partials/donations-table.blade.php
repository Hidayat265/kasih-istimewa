<div class="overflow-x-auto">
    @if($donations->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donation ID</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($donations as $donation)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $donation->donation_id }}</td>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $donation->donor_name }}</td>
                        <td class="px-4 py-2 text-sm font-semibold text-green-600">RM {{ number_format($donation->donation_amount, 2) }}</td>
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $donation->created_at ? $donation->created_at->format('d M Y') : 'N/A' }}</td>
                        <td class="px-4 py-2 text-sm">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $donation->donation_status === 'success' ? 'bg-green-100 text-green-800' : 
                                   ($donation->donation_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($donation->donation_status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            <div class="flex justify-center space-x-2">
                @if ($donations->onFirstPage())
                    <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Previous</span>
                @else
                    <a href="#" class="donations-pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $donations->currentPage() - 1 }}">Previous</a>
                @endif

                @php
                    $start = max(1, $donations->currentPage() - 2);
                    $end = min($donations->lastPage(), $donations->currentPage() + 2);
                @endphp

                @if ($start > 1)
                    <a href="#" class="donations-pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="1">1</a>
                    @if ($start > 2)
                        <span class="px-3 py-1 text-gray-400">...</span>
                    @endif
                @endif

                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $donations->currentPage())
                        <span class="px-3 py-1 bg-primary text-white rounded-md">{{ $i }}</span>
                    @else
                        <a href="#" class="donations-pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $i }}">{{ $i }}</a>
                    @endif
                @endfor

                @if ($end < $donations->lastPage())
                    @if ($end < $donations->lastPage() - 1)
                        <span class="px-3 py-1 text-gray-400">...</span>
                    @endif
                    <a href="#" class="donations-pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $donations->lastPage() }}">{{ $donations->lastPage() }}</a>
                @endif

                @if ($donations->hasMorePages())
                    <a href="#" class="donations-pagination-link px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition" data-page="{{ $donations->currentPage() + 1 }}">Next</a>
                @else
                    <span class="px-3 py-1 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>
    @else
        <p class="text-gray-500 text-sm">No donation records available yet.</p>
    @endif
</div>