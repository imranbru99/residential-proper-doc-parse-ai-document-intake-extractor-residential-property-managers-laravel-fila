<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceRequest extends Model
{
    protected $fillable = [
        'reference',
        'property_id',
        'resident_id',
        'resident_name',
        'resident_email',
        'resident_phone',
        'unit_number',
        'category',
        'priority',
        'status',
        'description',
        'messaging_consent',
        'fair_housing_flag',
        'assigned_vendor_id',
    ];

    protected function casts(): array
    {
        return [
            'messaging_consent' => 'boolean',
            'fair_housing_flag' => 'boolean',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'assigned_vendor_id');
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
