<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaultReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'reported_by',
        'assigned_to',
        'title',
        'description',
        'priority',
        'status',
        'closure_notes',
        'started_at',
        'completed_at',
        'closed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Get the asset this fault report belongs to
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Get the user who reported the fault
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the technician assigned to this fault
     */
    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Status badge colors
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Priority badge colors
     */
    public function getPriorityBadgeColorAttribute()
    {
        return match($this->priority) {
            'low' => 'bg-green-100 text-green-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-orange-100 text-orange-800',
            'critical' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Calculate resolution time in hours
     */
    public function getResolutionTimeAttribute()
    {
        if (!$this->completed_at) {
            return null;
        }
        return $this->created_at->diffInHours($this->completed_at);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by priority
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for assigned to specific technician
     */
    public function scopeAssignedTo($query, $technicianId)
    {
        return $query->where('assigned_to', $technicianId);
    }
}
