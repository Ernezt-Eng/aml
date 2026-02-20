@extends('layouts.app')

@section('title', 'Fault Report #' . $fault->id)

@section('content')
<div class="mb-6">
    <a href="{{ route('faults.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center mb-4">
        <i class="fas fa-arrow-left mr-2"></i> Back to Fault Reports
    </a>
    <div class="flex justify-between items-start">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-900">{{ $fault->title }}</h1>
                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $fault->status_badge_color }}">
                    {{ ucfirst(str_replace('_', ' ', $fault->status)) }}
                </span>
                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $fault->priority_badge_color }}">
                    {{ ucfirst($fault->priority) }} Priority
                </span>
            </div>
            <p class="text-gray-600">Fault Report #{{ $fault->id }} • {{ $fault->created_at->format('M d, Y h:i A') }}</p>
        </div>
        @if($fault->status !== 'closed')
            <a href="{{ route('faults.edit', $fault) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center">
                <i class="fas fa-edit mr-2"></i> Edit Report
            </a>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Fault Description -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Fault Description</h2>
            <p class="text-gray-700 whitespace-pre-line">{{ $fault->description }}</p>
        </div>

        <!-- Asset Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Asset Information</h2>
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-box text-blue-600 text-4xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-lg font-semibold text-gray-900">{{ $fault->asset->name }}</p>
                    <p class="text-sm text-gray-600 mt-1">Code: {{ $fault->asset->asset_code }}</p>
                    <p class="text-sm text-gray-600">Category: {{ $fault->asset->category }}</p>
                    <p class="text-sm text-gray-600">Location: {{ $fault->asset->location }}</p>
                    <a href="{{ route('assets.show', $fault->asset) }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                        View Asset Details →
                    </a>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                <i class="fas fa-history text-blue-600 mr-2"></i>Timeline
            </h2>
            <div class="space-y-4">
                <!-- Created -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-file-alt text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Fault Reported</p>
                        <p class="text-sm text-gray-600">{{ $fault->created_at->format('M d, Y h:i A') }}</p>
                        <p class="text-sm text-gray-500">By {{ $fault->reporter->name }}</p>
                    </div>
                </div>

                <!-- Started -->
                @if($fault->started_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                            <i class="fas fa-play text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Work Started</p>
                        <p class="text-sm text-gray-600">{{ $fault->started_at->format('M d, Y h:i A') }}</p>
                        <p class="text-sm text-gray-500">By {{ $fault->technician->name ?? 'Unknown' }}</p>
                    </div>
                </div>
                @endif

                <!-- Completed -->
                @if($fault->completed_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Work Completed</p>
                        <p class="text-sm text-gray-600">{{ $fault->completed_at->format('M d, Y h:i A') }}</p>
                        @if($fault->resolution_time)
                        <p class="text-sm text-gray-500">Resolution time: {{ $fault->resolution_time }} hours</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Closed -->
                @if($fault->closed_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-lock text-gray-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Fault Closed</p>
                        <p class="text-sm text-gray-600">{{ $fault->closed_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Closure Notes -->
        @if($fault->closure_notes)
        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-green-900 mb-2">
                <i class="fas fa-check-circle mr-2"></i>Closure Notes
            </h3>
            <p class="text-green-800 whitespace-pre-line">{{ $fault->closure_notes }}</p>
        </div>
        @endif
    </div>

    <!-- Sidebar Actions -->
    <div class="space-y-6">
        <!-- Status Management -->
        @if($fault->status !== 'closed')
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Management</h3>
            
            <form action="{{ route('faults.update-status', $fault) }}" method="POST" class="mb-4">
                @csrf
                @method('PATCH')
                <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3">
                    <option value="pending" {{ $fault->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $fault->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $fault->status === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-sync-alt mr-2"></i>Update Status
                </button>
            </form>

            @if($fault->status === 'completed')
            <button onclick="document.getElementById('closeModal').classList.remove('hidden')" 
                    class="w-full bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-lock mr-2"></i>Close Report
            </button>
            @endif
        </div>
        @endif

        <!-- Technician Assignment -->
        @if($fault->status !== 'closed')
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Assigned Technician</h3>
            
            @if($fault->technician)
                <div class="flex items-center mb-4 p-3 bg-blue-50 rounded-lg">
                    <div class="flex-shrink-0 h-10 w-10 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">{{ substr($fault->technician->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ $fault->technician->name }}</p>
                        <p class="text-xs text-gray-600">{{ $fault->technician->email }}</p>
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-500 mb-4 italic">No technician assigned</p>
            @endif

            <form action="{{ route('faults.assign-technician', $fault) }}" method="POST">
                @csrf
                @method('PATCH')
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $fault->technician ? 'Reassign to' : 'Assign to' }}
                </label>
                <select name="assigned_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-3">
                    <option value="">Select Technician</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" {{ $fault->assigned_to == $tech->id ? 'selected' : '' }}>
                            {{ $tech->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-user-check mr-2"></i>{{ $fault->technician ? 'Reassign' : 'Assign' }}
                </button>
            </form>
        </div>
        @endif

        <!-- Report Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Information</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600">Reported By</p>
                    <p class="font-medium text-gray-900">{{ $fault->reporter->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Report Date</p>
                    <p class="font-medium text-gray-900">{{ $fault->created_at->format('M d, Y') }}</p>
                </div>
                @if($fault->resolution_time)
                <div>
                    <p class="text-gray-600">Resolution Time</p>
                    <p class="font-medium text-gray-900">{{ $fault->resolution_time }} hours</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Close Report Modal -->
<div id="closeModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-lg w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Close Fault Report</h3>
        <form action="{{ route('faults.close', $fault) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="closure_notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Closure Notes <span class="text-red-500">*</span>
                </label>
                <textarea name="closure_notes" 
                          id="closure_notes" 
                          rows="6"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                          placeholder="Describe the resolution, actions taken, parts replaced, etc..."
                          required></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="document.getElementById('closeModal').classList.add('hidden')"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg font-medium">
                    <i class="fas fa-lock mr-2"></i>Close Report
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
