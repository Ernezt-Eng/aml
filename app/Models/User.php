<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get fault reports reported by this user
     */
    public function reportedFaults()
    {
        return $this->hasMany(FaultReport::class, 'reported_by');
    }

    /**
     * Get fault reports assigned to this user (technician)
     */
    public function assignedFaults()
    {
        return $this->hasMany(FaultReport::class, 'assigned_to');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is technician
     */
    public function isTechnician()
    {
        return $this->role === 'technician';
    }

    /**
     * Scope to get only technicians
     */
    public function scopeTechnicians($query)
    {
        return $query->where('role', 'technician');
    }
}
