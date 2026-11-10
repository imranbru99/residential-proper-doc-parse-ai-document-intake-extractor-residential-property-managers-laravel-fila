<?php

namespace App\Support;

use App\Models\FeatureModule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ModuleManager
{
    public static function all(): array
    {
        $config = config('modules', []);

        if (self::tableReady()) {
            $db = FeatureModule::query()->get()->keyBy('key');

            foreach ($config as $key => &$module) {
                if ($db->has($key)) {
                    $module['enabled'] = (bool) $db[$key]->enabled;
                    $module['name'] = $db[$key]->name ?: $module['name'];
                    $module['description'] = $db[$key]->description ?: $module['description'];
                }
            }
        }

        return $config;
    }

    public static function enabled(string $key): bool
    {
        return (bool) data_get(self::all(), "{$key}.enabled", false);
    }

    public static function enabledList(): array
    {
        return collect(self::all())->filter(fn ($m) => $m['enabled'] ?? false)->all();
    }

    public static function refresh(): void
    {
        Cache::forget('residentialproperdocparse.modules');
    }

    protected static function tableReady(): bool
    {
        try {
            return Schema::hasTable('feature_modules');
        } catch (\Throwable) {
            return false;
        }
    }
}
