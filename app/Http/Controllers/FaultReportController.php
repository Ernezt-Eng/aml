<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\FaultReport;
use App\Models\User;
use Illuminate\Http\Request;

class FaultReportController extends Controller
{
    public function index(Request $request)
    {
        $query = FaultReport::with(['asset', 'reporter', 'technician']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by assigned technician
        if ($request->filled('technician')) {
            $query->where('assigned_to', $request->technician);
        }

        $faults = $query->latest()->paginate(15);
        $technicians = User::technicians()->get();

        return view('faults.index', compact('faults', 'technicians'));
    }

    public function create(Request $request)
    {
        $assetId = $request->query('asset_id');
        $asset = $assetId ? Asset::find($assetId) : null;
        $assets = Asset::where('status', '!=', 'retired')->orderBy('name')->get();

        return view('faults.create', compact('assets', 'asset'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        $validated['reported_by'] = auth()->id();
        $validated['status'] = 'pending';

        FaultReport::create($validated);

        return redirect()->route('faults.index')
            ->with('success', 'Fault report created successfully!');
    }

    public function show(FaultReport $fault)
    {
        $fault->load(['asset', 'reporter', 'technician']);
        $technicians = User::technicians()->get();

        return view('faults.show', compact('fault', 'technicians'));
    }

    public function assignTechnician(Request $request, FaultReport $fault)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $fault->update($validated);

        return redirect()->route('faults.show', $fault)
            ->with('success', 'Technician assigned successfully!');
    }

    public function updateStatus(Request $request, FaultReport $fault)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,closed',
        ]);

        // Auto-set timestamps based on status
        $updates = $validated;
        
        if ($validated['status'] === 'in_progress' && !$fault->started_at) {
            $updates['started_at'] = now();
        }

        if ($validated['status'] === 'completed' && !$fault->completed_at) {
            $updates['completed_at'] = now();
        }

        if ($validated['status'] === 'closed' && !$fault->closed_at) {
            $updates['closed_at'] = now();
        }

        $fault->update($updates);

        return redirect()->route('faults.show', $fault)
            ->with('success', 'Status updated successfully!');
    }

    public function close(Request $request, FaultReport $fault)
    {
        $validated = $request->validate([
            'closure_notes' => 'required|string',
        ]);

        $fault->update([
            'closure_notes' => $validated['closure_notes'],
            'status' => 'closed',
            'closed_at' => now(),
            'completed_at' => $fault->completed_at ?? now(),
        ]);

        return redirect()->route('faults.show', $fault)
            ->with('success', 'Fault report closed successfully!');
    }

    public function edit(FaultReport $fault)
    {
        $assets = Asset::where('status', '!=', 'retired')->orderBy('name')->get();
        return view('faults.edit', compact('fault', 'assets'));
    }

    public function update(Request $request, FaultReport $fault)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        $fault->update($validated);

        return redirect()->route('faults.show', $fault)
            ->with('success', 'Fault report updated successfully!');
    }

    public function destroy(FaultReport $fault)
    {
        $fault->delete();

        return redirect()->route('faults.index')
            ->with('success', 'Fault report deleted successfully!');
    }
}
