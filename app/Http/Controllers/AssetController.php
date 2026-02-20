<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::withCount('faultReports')
            ->with('latestFaultReport')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_code' => 'required|string|max:255|unique:assets',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'status' => 'required|in:operational,maintenance,retired',
            'description' => 'nullable|string',
        ]);

        Asset::create($validated);

        return redirect()->route('assets.index')
            ->with('success', 'Asset created successfully!');
    }

    public function show(Asset $asset)
    {
        $asset->load(['faultReports' => function($query) {
            $query->with(['reporter', 'technician'])->latest();
        }]);

        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_code' => 'required|string|max:255|unique:assets,asset_code,' . $asset->id,
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'status' => 'required|in:operational,maintenance,retired',
            'description' => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Asset updated successfully!');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        return redirect()->route('assets.index')
            ->with('success', 'Asset deleted successfully!');
    }
}
