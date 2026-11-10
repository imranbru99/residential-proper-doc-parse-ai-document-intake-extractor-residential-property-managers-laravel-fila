<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureModule extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'enabled',
        'is_core',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'is_core' => 'boolean',
        ];
    }
}
