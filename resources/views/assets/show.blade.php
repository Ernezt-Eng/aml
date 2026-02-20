@extends('layouts.app')

@section('title', $asset->name . ' - Asset Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('assets.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center mb-4">
        <i class="fas fa-arrow-left mr-2"></i> Back to Assets
    </a>
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $asset->name }}</h1>
            <p class="text-gray-600 mt-1">{{ $asset->asset_code }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('faults.create', ['asset_id' => $asset->id]) }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i> Report Fault
            </a>
            <a href="{{ route('assets.edit', $asset) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center">
                <i class="fas fa-edit mr-2"></i> Edit Asset
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Asset Details Card -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Asset Information</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Category</p>
                <p class="text-lg font-medium text-gray-900">{{ $asset->category }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <div class="mt-1">
                    @if($asset->status === 'operational')
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Operational
                        </span>
                    @elseif($asset->status === 'maintenance')
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-wrench mr-1"></i> Maintenance
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                            <i class="fas fa-archive mr-1"></i> Retired
                        </span>
                    @endif
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600">Location</p>
                <p class="text-lg font-medium text-gray-900">
                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                    {{ $asset->location }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Fault Reports</p>
                <p class="text-lg font-medium text-gray-900">{{ $asset->faultReports->count() }}</p>
            </div>
            @if($asset->purchase_date)
            <div>
                <p class="text-sm text-gray-600">Purchase Date</p>
                <p class="text-lg font-medium text-gray-900">{{ $asset->purchase_date->format('M d, Y') }}</p>
            </div>
            @endif
            @if($asset->warranty_expiry)
            <div>
                <p class="text-sm text-gray-600">Warranty Status</p>
                <p class="text-lg font-medium {{ $asset->isUnderWarranty() ? 'text-green-600' : 'text-red-600' }}">
                    @if($asset->isUnderWarranty())
                        <i class="fas fa-shield-alt mr-1"></i> Valid until {{ $asset->warranty_expiry->format('M d, Y') }}
                    @else
                        <i class="fas fa-exclamation-circle mr-1"></i> Expired on {{ $asset->warranty_expiry->format('M d, Y') }}
                    @endif
                </p>
            </div>
            @endif
        </div>

        @if($asset->description)
        <div class="mt-6 pt-6 border-t">
            <p class="text-sm text-gray-600 mb-2">Description</p>
            <p class="text-gray-900">{{ $asset->description }}</p>
        </div>
        @endif
    </div>

    <!-- Quick Stats Card -->
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Faults</span>
                    <span class="text-2xl font-bold text-gray-900">{{ $asset->faultReports->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Active</span>
                    <span class="text-2xl font-bold text-red-600">
                        {{ $asset->faultReports->whereIn('status', ['pending', 'in_progress'])->count() }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Completed</span>
                    <span class="text-2xl font-bold text-green-600">
                        {{ $asset->faultReports->where('status', 'completed')->count() }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Closed</span>
                    <span class="text-2xl font-bold text-gray-600">
                        {{ $asset->faultReports->where('status', 'closed')->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fault Reports History -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-900">
            <i class="fas fa-history text-blue-600 mr-2"></i>
            Fault Reports History
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reported By</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($asset->faultReports as $fault)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $fault->title }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($fault->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $fault->priority_badge_color }}">
                                {{ ucfirst($fault->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $fault->status_badge_color }}">
                                {{ ucfirst(str_replace('_', ' ', $fault->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $fault->reporter->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $fault->technician->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $fault->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('faults.show', $fault) }}" class="text-blue-600 hover:text-blue-900">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-check-circle text-green-400 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg">No fault reports for this asset</p>
                            <a href="{{ route('faults.create', ['asset_id' => $asset->id]) }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                Report the first fault
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
