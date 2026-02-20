@extends('layouts.app')

@section('title', isset($asset) ? 'Edit Asset' : 'Create Asset')

@section('content')
<div class="mb-6">
    <a href="{{ route('assets.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center mb-4">
        <i class="fas fa-arrow-left mr-2"></i> Back to Assets
    </a>
    <h1 class="text-3xl font-bold text-gray-900">{{ isset($asset) ? 'Edit Asset' : 'Create New Asset' }}</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6 max-w-3xl">
    <form action="{{ isset($asset) ? route('assets.update', $asset) : route('assets.store') }}" method="POST">
        @csrf
        @if(isset($asset))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Asset Name -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Asset Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name', $asset->name ?? '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Asset Code -->
            <div>
                <label for="asset_code" class="block text-sm font-medium text-gray-700 mb-2">
                    Asset Code <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="asset_code" 
                       id="asset_code" 
                       value="{{ old('asset_code', $asset->asset_code ?? '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
                @error('asset_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    Category <span class="text-red-500">*</span>
                </label>
                <select name="category" 
                        id="category"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="">Select Category</option>
                    @foreach(['HVAC', 'Elevator', 'Generator', 'Safety', 'Electrical', 'Plumbing', 'IT Equipment', 'Other'] as $cat)
                        <option value="{{ $cat }}" {{ old('category', $asset->category ?? '') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location -->
            <div class="md:col-span-2">
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                    Location <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="location" 
                       id="location" 
                       value="{{ old('location', $asset->location ?? '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="e.g., Building A - 3rd Floor"
                       required>
                @error('location')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Purchase Date -->
            <div>
                <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Purchase Date
                </label>
                <input type="date" 
                       name="purchase_date" 
                       id="purchase_date" 
                       value="{{ old('purchase_date', isset($asset) && $asset->purchase_date ? $asset->purchase_date->format('Y-m-d') : '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('purchase_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Warranty Expiry -->
            <div>
                <label for="warranty_expiry" class="block text-sm font-medium text-gray-700 mb-2">
                    Warranty Expiry
                </label>
                <input type="date" 
                       name="warranty_expiry" 
                       id="warranty_expiry" 
                       value="{{ old('warranty_expiry', isset($asset) && $asset->warranty_expiry ? $asset->warranty_expiry->format('Y-m-d') : '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('warranty_expiry')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" 
                        id="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="operational" {{ old('status', $asset->status ?? 'operational') == 'operational' ? 'selected' : '' }}>
                        Operational
                    </option>
                    <option value="maintenance" {{ old('status', $asset->status ?? '') == 'maintenance' ? 'selected' : '' }}>
                        Maintenance
                    </option>
                    <option value="retired" {{ old('status', $asset->status ?? '') == 'retired' ? 'selected' : '' }}>
                        Retired
                    </option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Additional details about the asset...">{{ old('description', $asset->description ?? '') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
            <a href="{{ route('assets.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium inline-flex items-center">
                <i class="fas fa-save mr-2"></i>
                {{ isset($asset) ? 'Update Asset' : 'Create Asset' }}
            </button>
        </div>
    </form>
</div>
@endsection
