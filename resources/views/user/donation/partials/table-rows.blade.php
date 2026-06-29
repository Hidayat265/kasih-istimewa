@forelse($donations as $index => $donation)
<tr class="hover:bg-gray-50 transition">
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm text-gray-500">{{ ($donations->currentPage() - 1) * $donations->perPage() + $loop->iteration }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm font-semibold">{{ $donation->donation_id }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm font-semibold text-green-600">RM {{ number_format($donation->donation_amount ?? $donation->amount ?? 0, 2) }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm text-gray-600">{{ $donation->updated_at ? \Carbon\Carbon::parse($donation->updated_at)->format('d M Y, h:i A') : 'N/A' }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm text-gray-600">{{ ucfirst($donation->donation_payment_method ?? 'Manual') }}</span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="text-sm text-gray-600">
            @if($donation->donation_received_by && str_starts_with($donation->donation_received_by, 'USR'))
                @php
                    $admin = \App\Models\User::where('user_id', $donation->donation_received_by)->first();
                @endphp
                {{ $admin ? $admin->user_name : $donation->donation_received_by }}
            @else
                {{ ucfirst($donation->donation_received_by ?? 'Manual') }}
            @endif
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="px-2 py-1 text-xs font-semibold rounded-full 
            @if(in_array($donation->donation_status, ['success', 'completed'])) bg-green-100 text-green-800
            @elseif($donation->donation_status == 'pending') bg-yellow-100 text-yellow-800
            @elseif($donation->donation_status == 'failed') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ ucfirst($donation->donation_status ?? 'Completed') }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm">
        @if(in_array($donation->donation_status, ['success', 'completed']))
            <button onclick="viewReceipt('{{ $donation->donation_id }}')" 
                    class="text-primary hover:text-primary/80 transition font-medium">
                <i class="fas fa-file-pdf mr-1"></i> View Receipt
            </button>
        @else
            <span class="text-gray-400 text-sm">
                <i class="fas fa-file-pdf mr-1"></i> No Receipt
            </span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
        <i class="fas fa-hand-holding-heart text-4xl mb-3 block"></i>
        <p>No donations found.</p>
        <a href="{{ route('checkout') }}" class="inline-block mt-3 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
            Make Your First Donation
        </a>
    </td>
</tr>
@endforelse