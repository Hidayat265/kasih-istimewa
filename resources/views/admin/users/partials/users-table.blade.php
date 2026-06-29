@forelse ($users ?? [] as $user) 
    <tr>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
        </td>
        <td class="px-1 md:px-2 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="h-10 w-10 flex-shrink-0">
                    <x-avatar :user="$user" size="40" />
                </div>
            </div>
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            {{ $user->user_id }}
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ $user->user_name }}
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-600">
            {{ $user->user_email }}
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $user->user_phone_number ?? 'N/A' }}
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm text-gray-500">
            @if ($user->user_dob)
                @php
                    try { 
                        $dob = new DateTime($user->user_dob); 
                        $now = new DateTime(); 
                        $age = $now->diff($dob)->y; 
                        echo $age; 
                    } catch (Exception $e) { 
                        echo 'Invalid Date'; 
                    }
                @endphp
            @else
                N/A
            @endif
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap">
            @if($user->isActive())
                <span class="px-2.5 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                    <i class="fas fa-circle text-green-500 text-[6px] mr-1.5"></i>
                    Active
                </span>
            @else
                <span class="px-2.5 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                    <i class="fas fa-circle text-red-500 text-[6px] mr-1.5"></i>
                    Deactivated
                </span>
            @endif
        </td>
        <td class="px-3 md:px-4 py-4 whitespace-nowrap text-sm font-medium">
            <a href="{{ route('admin.users.profile', $user->user_id) }}" 
               class="inline-flex items-center px-3 py-1.5 bg-primary/10 text-primary text-xs font-semibold rounded-lg hover:bg-primary/20 transition-colors duration-200">
                <i class="fas fa-eye mr-1.5 text-xs"></i>
                View Details
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
            <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
            No users found.
        </td>
    </tr>
@endforelse