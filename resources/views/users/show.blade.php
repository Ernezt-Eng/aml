@extends('layouts.app')

@section('title', $user->name . ' - User Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center mb-4">
        <i class="fas fa-arrow-left mr-2"></i> Back to Users
    </a>
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="text-gray-600 mt-1">{{ $user->email }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center">
                <i class="fas fa-edit mr-2"></i> Edit User
            </a>
            @if($user->id !== auth()->id())
                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center">
                        <i class="fas fa-trash mr-2"></i> Delete User
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- User Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">User Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Full Name</p>
                    <p class="text-lg font-medium text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="text-lg font-medium text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Role</p>
                    <div class="mt-1">
                        @if($user->role === 'admin')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                <i class="fas fa-crown mr-1"></i> Administrator
                            </span>
                        @elseif($user->role === 'technician')
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                <i class="fas fa-wrench mr-1"></i> Technician
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                <i class="fas fa-user mr-1"></i> Regular User
                            </span>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Member Since</p>
                    <p class="text-lg font-medium text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Faults Reported -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                    Recent Faults Reported
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($user->reportedFaults()->latest()->limit(5)->get() as $fault)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $fault->asset->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $fault->title }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $fault->status_badge_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $fault->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $fault->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">No faults reported yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($user->isTechnician() || $user->isAdmin())
        <!-- Assigned Tasks -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-tasks text-blue-600 mr-2"></i>
                    Assigned Tasks
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asset</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($user->assignedFaults()->latest()->limit(5)->get() as $fault)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $fault->asset->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $fault->title }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $fault->priority_badge_color }}">
                                        {{ ucfirst($fault->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $fault->status_badge_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $fault->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $fault->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">No tasks assigned yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Stats Sidebar -->
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center mb-4">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600 rounded-full mb-3">
                    <span class="text-white font-bold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                </div>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Activity Summary</h3>
            <div class="space-y-4">
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <p class="text-sm text-red-600 mb-1">Faults Reported</p>
                    <p class="text-3xl font-bold text-red-700">{{ $stats['total_reported'] }}</p>
                </div>

                @if($user->isTechnician() || $user->isAdmin())
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-600 mb-1">Tasks Assigned</p>
                        <p class="text-3xl font-bold text-blue-700">{{ $stats['total_assigned'] }}</p>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-center p-3 bg-yellow-50 rounded">
                            <p class="text-xs text-yellow-600">Pending</p>
                            <p class="text-xl font-bold text-yellow-700">{{ $stats['pending_tasks'] }}</p>
                        </div>
                        <div class="text-center p-3 bg-purple-50 rounded">
                            <p class="text-xs text-purple-600">In Progress</p>
                            <p class="text-xl font-bold text-purple-700">{{ $stats['in_progress_tasks'] }}</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded">
                            <p class="text-xs text-green-600">Done</p>
                            <p class="text-xl font-bold text-green-700">{{ $stats['completed_tasks'] }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
