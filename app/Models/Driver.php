<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class Driver extends Authenticatable
{
    use Notifiable;

protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'address',
    'emergency_contact_name',
    'emergency_contact_phone',
    'license_number',
    'license_expiry',
    'employee_id',
    'hire_date',
    'photo',
    'date_of_birth',
    'gender',
    'status',
    'availability_status',
    'last_seen_at',
    'is_available',
    'certifications',
    'skills',
    'notes',
    'ambulance_id',
];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'license_expiry' => 'date',
        'hire_date' => 'date',
        'last_seen_at' => 'datetime',
        'is_available' => 'boolean',
        'certifications' => 'array',
        'skills' => 'array',
    ];

    public function ambulance()
{
    return $this->belongsTo(Ambulance::class);
}

    public function driverMedicPairings()
    {
        return $this->hasMany(DriverMedicPairing::class);
    }

    public function driverAmbulancePairings()
    {
        return $this->hasMany(DriverAmbulancePairing::class);
    }

    // Helper methods
    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return asset('image/default-driver.svg');
        }

        // Check if photo exists in new location (public/image)
        $newPath = public_path('image/' . $this->photo);
        if (file_exists($newPath)) {
            return asset('image/' . $this->photo);
        }

        // Fallback to old location (storage/app/public/driver-photos)
        // Accessible via storage symlink at public/storage
        $oldPath = storage_path('app/public/driver-photos/' . $this->photo);
        if (file_exists($oldPath)) {
            return asset('storage/driver-photos/' . $this->photo);
        }

        // If photo doesn't exist in either location, return default
        // This prevents 404 errors by returning a valid default image
        return asset('image/default-driver.svg');
    }

    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            /** @var Carbon $dateOfBirth */
            $dateOfBirth = $this->date_of_birth;
            return $dateOfBirth->diffInYears(now());
        }
        return null;
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function isOnline()
    {
        return $this->availability_status === 'online';
    }

    public function isOffline()
    {
        return $this->availability_status === 'offline';
    }

    public function isBusy()
    {
        return $this->availability_status === 'busy';
    }

    public function isOnBreak()
    {
        return $this->availability_status === 'on_break';
    }

    public function isLicenseExpired()
    {
        if (!$this->license_expiry) {
            return false;
        }
        /** @var Carbon $licenseExpiry */
        $licenseExpiry = $this->license_expiry;
        return $licenseExpiry->isPast();
    }

    public function isLicenseExpiringSoon($days = 30)
    {
        if (!$this->license_expiry) {
            return false;
        }
        /** @var Carbon $licenseExpiry */
        $licenseExpiry = $this->license_expiry;
        return $licenseExpiry->isFuture() && $licenseExpiry->diffInDays(now()) <= $days;
    }

    public function getStatusBadgeClass()
    {
        switch($this->status) {
            case 'active':
                return 'bg-green-100 text-green-800';
            case 'inactive':
                return 'bg-gray-100 text-gray-800';
            case 'suspended':
                return 'bg-red-100 text-red-800';
            case 'on_leave':
                return 'bg-yellow-100 text-yellow-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    public function getAvailabilityBadgeClass()
    {
        switch($this->availability_status) {
            case 'online':
                return 'bg-green-100 text-green-800';
            case 'offline':
                return 'bg-gray-100 text-gray-800';
            case 'busy':
                return 'bg-red-100 text-red-800';
            case 'on_break':
                return 'bg-yellow-100 text-yellow-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    public function updateLastSeen()
    {
        $this->update(['last_seen_at' => now()]);
    }

    public function setAvailabilityStatus($status)
    {
        $this->update([
            'availability_status' => $status,
            'last_seen_at' => now()
        ]);
    }

}
