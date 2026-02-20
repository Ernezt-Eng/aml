@extends('layouts.app')

@section('title', isset($fault) ? 'Edit Fault Report' : 'Report New Fault')

@section('content')
<div class="mb-6">
    <a href="{{ route('faults.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center mb-4">
        <i class="fas fa-arrow-left mr-2"></i> Back to Fault Reports
    </a>
    <h1 class="text-3xl font-bold text-gray-900">{{ isset($fault) ? 'Edit Fault Report' : 'Report New Fault' }}</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
    <form action="{{ isset($fault) ? route('faults.update', $fault) : route('faults.store') }}" method="POST">
        @csrf
        @if(isset($fault))
            @method('PUT')
        @endif

        <!-- Asset Selection -->
        <div class="mb-6">
            <label for="asset_id" class="block text-sm font-medium text-gray-700 mb-2">
                Asset <span class="text-red-500">*</span>
            </label>
            <select name="asset_id" 
                    id="asset_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                    {{ isset($asset) ? 'disabled' : '' }}>
                <option value="">Select an asset</option>
                @foreach($assets as $assetOption)
                    <option value="{{ $assetOption->id }}" 
                            {{ (old('asset_id', $asset->id ?? $fault->asset_id ?? '') == $assetOption->id) ? 'selected' : '' }}>
                        {{ $assetOption->name }} ({{ $assetOption->asset_code }}) - {{ $assetOption->location }}
                    </option>
                @endforeach
            </select>
            @if(isset($asset))
                <input type="hidden" name="asset_id" value="{{ $asset->id }}">
            @endif
            @error('asset_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Fault Title -->
        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                Fault Title <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   value="{{ old('title', $fault->title ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                   placeholder="Brief description of the issue"
                   required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Detailed Description <span class="text-red-500">*</span>
            </label>
            <textarea name="description" 
                      id="description" 
                      rows="6"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="Provide detailed information about the fault, when it was noticed, and any relevant observations..."
                      required>{{ old('description', $fault->description ?? '') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Priority -->
        <div class="mb-6">
            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                Priority Level <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach(['low' => ['color' => 'green', 'icon' => 'info-circle'], 
                          'medium' => ['color' => 'yellow', 'icon' => 'exclamation-circle'], 
                          'high' => ['color' => 'orange', 'icon' => 'exclamation-triangle'], 
                          'critical' => ['color' => 'red', 'icon' => 'fire']] as $priorityLevel => $config)
                    <label class="relative cursor-pointer">
                        <input type="radio" 
                               name="priority" 
                               value="{{ $priorityLevel }}"
                               class="peer sr-only"
                               {{ old('priority', $fault->priority ?? 'medium') === $priorityLevel ? 'checked' : '' }}
                               required>
                        <div class="border-2 border-gray-300 rounded-lg p-4 text-center peer-checked:border-{{ $config['color'] }}-500 peer-checked:bg-{{ $config['color'] }}-50 hover:bg-gray-50 transition">
                            <i class="fas fa-{{ $config['icon'] }} text-{{ $config['color'] }}-600 text-2xl mb-2"></i>
                            <p class="text-sm font-semibold text-gray-900 capitalize">{{ $priorityLevel }}</p>
                        </div>
                    </label>
                @endforeach
            </div>
            @error('priority')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
            <a href="{{ route('faults.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium inline-flex items-center">
                <i class="fas fa-paper-plane mr-2"></i>
                {{ isset($fault) ? 'Update Report' : 'Submit Report' }}
            </button>
        </div>
    </form>
</div>

@if(isset($asset))
    <!-- Asset Preview Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 max-w-3xl mt-6">
        <p class="text-sm font-medium text-blue-900 mb-2">Reporting fault for:</p>
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-box text-blue-600 text-3xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-lg font-semibold text-blue-900">{{ $asset->name }}</p>
                <p class="text-sm text-blue-700">{{ $asset->asset_code }} • {{ $asset->location }}</p>
            </div>
        </div>
    </div>
@endif
@endsection
