<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Support\ModuleManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public static function log(string $action, ?Model $entity = null, array $payload = []): ?AuditLog
    {
        if (! ModuleManager::enabled('audit_log')) {
            return null;
        }

        return AuditLog::query()->create([
            'action' => $action,
            'entity_type' => $entity ? $entity::class : null,
            'entity_id' => $entity?->getKey(),
            'user_id' => Auth::id(),
            'payload' => $payload,
            'ip_address' => Request::ip(),
        ]);
    }
}
