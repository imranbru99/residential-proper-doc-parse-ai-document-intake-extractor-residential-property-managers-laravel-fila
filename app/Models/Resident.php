<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resident extends Model
{
    protected $fillable = [
        'property_id',
        'user_id',
        'name',
        'email',
        'phone',
        'unit_number',
        'lease_status',
        'messaging_consent',
        'privacy_restricted',
    ];

    protected function casts(): array
    {
        return [
            'messaging_consent' => 'boolean',
            'privacy_restricted' => 'boolean',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }
}
