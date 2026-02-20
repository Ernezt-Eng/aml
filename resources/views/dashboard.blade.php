@extends('layouts.app')

@section('title', 'Dashboard - Asset Maintenance Logger')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="text-gray-600 mt-1">Overview of asset maintenance activities and analytics</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-box text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Total Assets</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_assets'] }}</p>
            </div>
        </div>
        <div class="mt-4 text-sm">
            <span class="text-green-600 font-medium">{{ $stats['operational_assets'] }} operational</span>
            <span class="text-gray-400"> | </span>
            <span class="text-yellow-600 font-medium">{{ $stats['maintenance_assets'] }} in maintenance</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Pending Faults</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_faults'] }}</p>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            Awaiting assignment or action
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-cog text-2xl fa-spin"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">In Progress</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['in_progress_faults'] }}</p>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            Currently being worked on
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Completed (This Month)</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_this_month'] }}</p>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            Resolved in {{ now()->format('F Y') }}
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Monthly Faults Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-chart-line text-blue-600 mr-2"></i>
            Monthly Fault Reports (Last 6 Months)
        </h3>
        <div class="space-y-4">
            @foreach($monthlyFaults as $month)
                @php
                    $maxCount = $monthlyFaults->max('count');
                    $percentage = $maxCount > 0 ? ($month->count / $maxCount) * 100 : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">{{ date('M Y', strtotime($month->month . '-01')) }}</span>
                        <span class="font-semibold text-gray-900">{{ $month->count }} reports</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Status & Priority Distribution -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-pie-chart text-green-600 mr-2"></i>
            Distribution Overview
        </h3>
        
        <div class="mb-6">
            <p class="text-sm font-medium text-gray-700 mb-3">By Status</p>
            <div class="grid grid-cols-2 gap-3">
                @foreach(['pending' => 'yellow', 'in_progress' => 'blue', 'completed' => 'green', 'closed' => 'gray'] as $status => $color)
                    <div class="bg-{{ $color }}-50 border border-{{ $color }}-200 rounded-lg p-3">
                        <p class="text-xs text-{{ $color }}-600 uppercase font-semibold">{{ str_replace('_', ' ', $status) }}</p>
                        <p class="text-2xl font-bold text-{{ $color }}-700">{{ $faultsByStatus[$status] ?? 0 }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <p class="text-sm font-medium text-gray-700 mb-3">By Priority</p>
            <div class="grid grid-cols-2 gap-3">
                @foreach(['low' => 'green', 'medium' => 'yellow', 'high' => 'orange', 'critical' => 'red'] as $priority => $color)
                    <div class="bg-{{ $color }}-50 border border-{{ $color }}-200 rounded-lg p-3">
                        <p class="text-xs text-{{ $color }}-600 uppercase font-semibold">{{ $priority }}</p>
                        <p class="text-2xl font-bold text-{{ $color }}-700">{{ $faultsByPriority[$priority] ?? 0 }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Additional Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Top Assets by Fault Count -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-trophy text-yellow-600 mr-2"></i>
            Top Assets by Fault Reports
        </h3>
        <div class="space-y-3">
            @forelse($topAssets as $asset)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $asset->name }}</p>
                        <p class="text-sm text-gray-600">{{ $asset->asset_code }} • {{ $asset->category }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                            {{ $asset->fault_reports_count }} faults
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No data available</p>
            @endforelse
        </div>
    </div>

    <!-- Technician Workload -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-users-cog text-purple-600 mr-2"></i>
            Technician Workload
        </h3>
        <div class="space-y-3">
            @forelse($technicianWorkload as $tech)
                @php
                    $maxWorkload = $technicianWorkload->max('active_faults');
                    $percentage = $maxWorkload > 0 ? ($tech->active_faults / $maxWorkload) * 100 : 0;
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-900">{{ $tech->name }}</span>
                        <span class="text-gray-600">{{ $tech->active_faults }} active tasks</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No technicians assigned</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Faults -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">
            <i class="fas fa-clock text-blue-600 mr-2"></i>
            Recent Fault Reports
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Technician</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentFaults as $fault)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $fault->asset->name }}</div>
                            <div class="text-sm text-gray-500">{{ $fault->asset->asset_code }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $fault->title }}</div>
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
                            {{ $fault->technician->name ?? 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $fault->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No fault reports found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
