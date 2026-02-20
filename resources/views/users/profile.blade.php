@extends('layouts.app')

@section('title', 'My Profile - Asset Maintenance Logger')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
    <p class="text-gray-600 mt-1">Manage your account settings</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Account Information</h2>
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $user->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Role
                    </label>
                    <input type="text" 
                           value="{{ ucfirst($user->role) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50"
                           disabled>
                    <p class="text-xs text-gray-500 mt-1">Contact an administrator to change your role</p>
                </div>

                <div class="border-t pt-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
                    
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Current Password
                        </label>
                        <input type="password" 
                               name="current_password" 
                               id="current_password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Leave blank to keep current password">
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Minimum 8 characters">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm New Password
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Re-enter new password">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                        <i class="fas fa-save mr-2"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Sidebar -->
    <div class="space-y-6">
        <!-- User Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-center mb-4">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600 rounded-full mb-3">
                    <span class="text-white font-bold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full 
                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'technician' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
            <div class="text-center text-sm text-gray-600 pt-4 border-t">
                <p>Member since</p>
                <p class="font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Activity Stats -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">My Activity</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Faults Reported</span>
                    <span class="text-lg font-bold text-blue-600">{{ $stats['total_reported'] }}</span>
                </div>
                
                @if($user->isTechnician() || $user->isAdmin())
                    <div class="border-t pt-3">
                        <p class="text-xs font-semibold text-gray-700 mb-2">As Technician</p>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Assigned</span>
                                <span class="font-semibold text-gray-900">{{ $stats['total_assigned'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pending</span>
                                <span class="font-semibold text-yellow-600">{{ $stats['pending_tasks'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">In Progress</span>
                                <span class="font-semibold text-blue-600">{{ $stats['in_progress_tasks'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Completed</span>
                                <span class="font-semibold text-green-600">{{ $stats['completed_tasks'] }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
