<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorWorkOrder extends Model
{
    protected $fillable = [
        'work_order_number',
        'maintenance_request_id',
        'document_intake_id',
        'vendor_id',
        'property_id',
        'title',
        'scope_of_work',
        'status',
        'scheduled_at',
        'unit_number',
        'site_address',
        'compliance_notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'compliance_notes' => 'array',
        ];
    }

    public function maintenanceRequest(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }

    public function documentIntake(): BelongsTo
    {
        return $this->belongsTo(DocumentIntake::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
