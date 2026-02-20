@extends('layouts.app')

@section('title', 'Assets - Asset Maintenance Logger')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Assets</h1>
        <p class="text-gray-600 mt-1">Manage all equipment and machinery</p>
    </div>
    <a href="{{ route('assets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center transition">
        <i class="fas fa-plus mr-2"></i> Add New Asset
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Info</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Faults</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Warranty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($assets as $asset)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $asset->name }}</div>
                            <div class="text-sm text-gray-500">{{ $asset->asset_code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ $asset->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                            {{ $asset->location }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($asset->status === 'operational')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Operational
                                </span>
                            @elseif($asset->status === 'maintenance')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-wrench mr-1"></i> Maintenance
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <i class="fas fa-archive mr-1"></i> Retired
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($asset->fault_reports_count > 0)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    {{ $asset->fault_reports_count }} reports
                                </span>
                            @else
                                <span class="text-sm text-gray-500">None</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($asset->isUnderWarranty())
                                <span class="text-green-600">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    Valid until {{ $asset->warranty_expiry->format('M Y') }}
                                </span>
                            @else
                                <span class="text-gray-500">Expired</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('assets.show', $asset) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('assets.edit', $asset) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('faults.create', ['asset_id' => $asset->id]) }}" class="text-red-600 hover:text-red-900" title="Report Fault">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-box text-gray-400 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg">No assets found</p>
                            <a href="{{ route('assets.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                Add your first asset
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
