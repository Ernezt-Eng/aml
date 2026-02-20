<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\FaultReport;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Overall statistics
        $stats = [
            'total_assets' => Asset::count(),
            'operational_assets' => Asset::where('status', 'operational')->count(),
            'maintenance_assets' => Asset::where('status', 'maintenance')->count(),
            'total_faults' => FaultReport::count(),
            'pending_faults' => FaultReport::where('status', 'pending')->count(),
            'in_progress_faults' => FaultReport::where('status', 'in_progress')->count(),
            'completed_this_month' => FaultReport::whereMonth('completed_at', now()->month)
                ->whereYear('completed_at', now()->year)
                ->count(),
        ];

        // Monthly fault reports (last 6 months)
        $monthlyFaults = FaultReport::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Faults by priority
        $faultsByPriority = FaultReport::select('priority', DB::raw('COUNT(*) as count'))
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority');

        // Faults by status
        $faultsByStatus = FaultReport::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Average resolution time by month
        $avgResolutionTime = FaultReport::select(
                DB::raw('DATE_FORMAT(completed_at, "%Y-%m") as month'),
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as avg_hours')
            )
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top 5 assets with most faults
        $topAssets = Asset::withCount('faultReports')
            ->orderBy('fault_reports_count', 'desc')
            ->limit(5)
            ->get();

        // Recent faults
        $recentFaults = FaultReport::with(['asset', 'reporter', 'technician'])
            ->latest()
            ->limit(10)
            ->get();

        // Technician workload
        $technicianWorkload = DB::table('users')
            ->where('role', 'technician')
            ->leftJoin('fault_reports', function($join) {
                $join->on('users.id', '=', 'fault_reports.assigned_to')
                     ->whereIn('fault_reports.status', ['pending', 'in_progress']);
            })
            ->select('users.name', DB::raw('COUNT(fault_reports.id) as active_faults'))
            ->groupBy('users.id', 'users.name')
            ->get();

        // Monthly analytics by category
        $monthlyByCategory = Asset::select(
                'assets.category',
                DB::raw('DATE_FORMAT(fault_reports.created_at, "%Y-%m") as month'),
                DB::raw('COUNT(fault_reports.id) as count')
            )
            ->join('fault_reports', 'assets.id', '=', 'fault_reports.asset_id')
            ->where('fault_reports.created_at', '>=', now()->subMonths(6))
            ->groupBy('assets.category', 'month')
            ->orderBy('month')
            ->get()
            ->groupBy('category');

        return view('dashboard', compact(
            'stats',
            'monthlyFaults',
            'faultsByPriority',
            'faultsByStatus',
            'avgResolutionTime',
            'topAssets',
            'recentFaults',
            'technicianWorkload',
            'monthlyByCategory'
        ));
    }
}
