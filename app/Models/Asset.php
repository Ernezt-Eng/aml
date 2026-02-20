<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'asset_code',
        'category',
        'location',
        'purchase_date',
        'warranty_expiry',
        'status',
        'description',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
    ];

    /**
     * Get all fault reports for this asset
     */
    public function faultReports()
    {
        return $this->hasMany(FaultReport::class);
    }

    /**
     * Get active fault reports
     */
    public function activeFaultReports()
    {
        return $this->hasMany(FaultReport::class)
            ->whereIn('status', ['pending', 'in_progress']);
    }

    /**
     * Check if asset is under warranty
     */
    public function isUnderWarranty()
    {
        return $this->warranty_expiry && $this->warranty_expiry->isFuture();
    }

    /**
     * Get the latest fault report
     */
    public function latestFaultReport()
    {
        return $this->hasOne(FaultReport::class)->latestOfMany();
    }
}
