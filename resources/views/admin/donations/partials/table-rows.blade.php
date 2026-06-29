@forelse($donations as $index => $donation)
    @php
        $rowNumber = ($donations->currentPage() - 1) * $donations->perPage() + $index + 1;
    @endphp
    <tr data-donation-id="{{ $donation->donation_id }}" data-amount="{{ $donation->donation_amount }}" data-created-at="{{ $donation->created_at }}">
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $rowNumber }}</td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $donation->donation_id }}</td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-900">{{ $donation->donor_name }}</td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-600">
            @php
                $user = \App\Models\User::where('user_email', $donation->donor_email)->first();
            @endphp
            @if($user)
                <a href="{{ route('admin.users.profile', $user->user_id) }}" class="text-secondary hover:text-primary underline transition-colors">
                    {{ $donation->donor_email }}
                </a>
            @else
                <span class="text-gray-500">{{ $donation->donor_email ?? 'N/A' }}</span>
            @endif
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $donation->donor_phone ?? 'N/A' }}</td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm font-semibold text-primary donation-amount">RM {{ number_format($donation->donation_amount, 2) }}</td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-500 donation-date">{{ $donation->updated_at->format('d M Y, h:i A') }}</td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm">
            @php
                $method = $donation->donation_payment_method;
                $methodDisplay = match(strtolower($method)) {
                    'cash' => 'Cash',
                    'toyyibpay' => 'ToyyibPay',
                    'stripe' => 'Stripe',
                    default => ucfirst($method),
                };
            @endphp
            <span class="px-2.5 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                {{ $methodDisplay }}
            </span>
        </td>
        
        <td class="px-3 md:px-4 py-4 whitespace-nowrap">
            @php
                $receivedBy = $donation->donation_received_by;
                $displayName = $receivedBy;
                $isUser = false;
                $userId = null;
                
                if (is_string($receivedBy) && str_starts_with($receivedBy, 'USR-')) {
                    $user = \App\Models\User::where('user_id', $receivedBy)->first();
                    if ($user) {
                        $isUser = true;
                        $userId = $user->user_id;
                        $displayName = $user->user_name;
                    }
                } elseif (strtolower($receivedBy) === 'toyyibpay') {
                    $displayName = 'ToyyibPay';
                } elseif (strtolower($receivedBy) === 'stripe') {
                    $displayName = 'Stripe';
                } elseif ($receivedBy === 'Cash' || $receivedBy === 'cash') {
                    $displayName = 'Cash';
                }
            @endphp
            
            @if($isUser && $userId)
                <a href="{{ route('admin.users.profile', $userId) }}" 
                   class="px-2.5 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full hover:bg-purple-200 transition-colors">
                    {{ $displayName }}
                </a>
            @else
                @if($displayName === 'ToyyibPay' || $displayName === 'Stripe')
                    <span class="px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                        {{ $displayName }}
                    </span>
                @elseif($displayName === 'Cash')
                    <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                        {{ $displayName }}
                    </span>
                @else
                    <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                        {{ $displayName }}
                    </span>
                @endif
            @endif
        </td>
        
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $donation->donation_transaction_id ?? 'N/A' }}
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap">
            @php
                $statusColor = match($donation->donation_status) {
                    'pending' => 'yellow',
                    'success' => 'green',
                    'failed' => 'red',
                    default => 'gray',
                };
            @endphp
            <span class="px-2.5 py-1 text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 rounded-full status-badge">
                {{ ucfirst($donation->donation_status) }}
            </span>
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm font-medium">
            <a href="{{ route('donation.receipt', $donation->donation_id) }}" class="text-secondary hover:text-primary transition-colors duration-200" title="View Receipt">
                <i class="fas fa-file-pdf mr-1"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="12" class="px-4 py-8 text-center text-gray-500">
            <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
            No donations found.
        </td>
    </tr>
@endforelse