<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DocumentIntake extends Model
{
    protected $fillable = [
        'reference',
        'source',
        'original_filename',
        'mime_type',
        'storage_path',
        'document_type',
        'status',
        'confidence_score',
        'raw_text',
        'extracted_fields',
        'validation_errors',
        'exception_reason',
        'routed_queue',
        'property_id',
        'maintenance_request_id',
        'uploaded_by',
        'approved_by',
        'approved_at',
        'extracted_at',
    ];

    protected function casts(): array
    {
        return [
            'raw_text' => 'array',
            'extracted_fields' => 'array',
            'validation_errors' => 'array',
            'confidence_score' => 'decimal:2',
            'approved_at' => 'datetime',
            'extracted_at' => 'datetime',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function maintenanceRequest(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approval(): HasOne
    {
        return $this->hasOne(Approval::class);
    }

    public function vendorWorkOrders(): HasMany
    {
        return $this->hasMany(VendorWorkOrder::class);
    }
}
