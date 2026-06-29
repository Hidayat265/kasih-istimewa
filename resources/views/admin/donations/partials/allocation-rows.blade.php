@forelse($allocations as $index => $allocation)
    @php
        $allocationDate = \Carbon\Carbon::parse($allocation->allocation_month . '-01');
        $currentDate = now();
        $monthsDifference = $currentDate->diffInMonths($allocationDate, false);
        $canEdit = $monthsDifference >= -1;
    @endphp

    <tr
        data-allocation-id="{{ $allocation->allocation_id }}"
        data-category="{{ $allocation->category->alc_cat_name ?? '' }}"
        data-month="{{ $allocation->allocation_month }}"
    >
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $allocations->firstItem() + $index }}
        </td>

        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-900">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-full flex items-center justify-center"
                    style="background-color: {{ $allocation->category->alc_cat_color ?? '#554994' }};"
                >
                    <i
                        class="{{ $allocation->category->alc_cat_icon ?? 'fas fa-heart' }} text-white text-sm">
                    </i>
                </div>

                <div class="font-medium text-gray-800">
                    {{ $allocation->category->alc_cat_name ?? 'No Category' }}
                </div>
            </div>
        </td>

        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-600">
            {{ \Carbon\Carbon::parse($allocation->allocation_month . '-01')->format('F Y') }}
        </td>

        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm font-semibold text-primary">
            {{ number_format($allocation->allocation_percent, 2) }}%
        </td>

        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
            RM {{ number_format($allocation->allocation_amount, 2) }}
        </td>

        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-600">
            {{ $allocation->allocation_notes ?? '-' }}
        </td>

        <td class="px-3 md:px-4 py-4 whitespace-nowrap">
            @if($allocation->changedByUser)
                <a href="{{ route('admin.users.profile', $allocation->changedByUser->user_id) }}" 
                class="px-2.5 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full hover:bg-gray-200 transition-colors">
                    {{ $allocation->changedByUser->user_name }}
                </a>
            @else
                <span class="px-2.5 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">
                    System
                </span>
            @endif
        </td>

        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">

            @if($canEdit)
                <button
                    type="button"
                    class="text-blue-500 hover:text-blue-700 transition-colors duration-200"
                    title="Edit allocation"
                    onclick="openEditAllocationModal(
                        '{{ $allocation->allocation_id }}',
                        '{{ $allocation->allocation_month }}',
                        '{{ $allocation->allocation_category_id }}',
                        '{{ $allocation->allocation_percent }}',
                        '{{ addslashes($allocation->allocation_notes ?? '') }}'
                    )"
                >
                    <i class="fas fa-edit"></i>
                </button>
            @else
                <span
                    class="text-gray-300 cursor-not-allowed"
                    title="Cannot edit allocations older than 2 months"
                >
                    <i class="fas fa-lock"></i>
                </span>
            @endif

            <button
                type="button"
                onclick="deleteAllocation('{{ $allocation->allocation_id }}', '{{ addslashes($allocation->category->alc_cat_name ?? 'Unnamed') }}', '{{ $allocation->allocation_month }}')"
                class="text-red-500 hover:text-red-700 transition-colors duration-200"
                title="Delete allocation"
            >
                <i class="fas fa-trash"></i>
            </button>

        </td>
    </tr>

@empty

    <tr>
        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
            <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
            No allocations found for this month.
        </td>
    </tr>

@endforelse