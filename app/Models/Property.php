<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    protected $fillable = [
        'code',
        'name',
        'address',
        'city',
        'state',
        'postal_code',
        'unit_count',
        'property_type',
        'status',
    ];

    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class);
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function documentIntakes(): HasMany
    {
        return $this->hasMany(DocumentIntake::class);
    }

    public function vendorWorkOrders(): HasMany
    {
        return $this->hasMany(VendorWorkOrder::class);
    }
}
