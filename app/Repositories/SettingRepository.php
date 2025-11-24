<?php

namespace App\Repositories;

use App\Interfaces\SettingRepositoryInterface;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class SettingRepository implements SettingRepositoryInterface
{
    public function getAll(): Collection
    {
        return Setting::all();
    }

    public function getByKey(string $key): ?Setting
    {
        return Setting::where('key', $key)->first();
    }

    public function getValue(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = $this->getByKey($key);
            return $setting ? $setting->getCastValue() : $default;
        });
    }

    public function setValue(string $key, $value, string $group = 'general', string $type = 'string', string $description = null): bool
    {
        $setting = Setting::firstOrNew(['key' => $key]);
        
        $setting->fill([
            'value' => $value,
            'type' => $type,
            'group' => $group,
            'description' => $description
        ]);

        $saved = $setting->save();

        if ($saved) {
            Cache::forget("setting.{$key}");
            Cache::forget("settings.group.{$group}");
        }

        return $saved;
    }

    public function getByGroup(string $group): array
    {
        return Cache::remember("settings.group.{$group}", 3600, function () use ($group) {
            return Setting::where('group', $group)
                ->get()
                ->mapWithKeys(function ($setting) {
                    return [$setting->key => $setting->getCastValue()];
                })
                ->toArray();
        });
    }

    public function updateMultiple(array $settings): bool
    {
        try {
            foreach ($settings as $key => $value) {
                $setting = Setting::where('key', $key)->first();
                
                if ($setting) {
                    $setting->update(['value' => $value]);
                    Cache::forget("setting.{$key}");
                    Cache::forget("settings.group.{$setting->group}");
                }
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteByKey(string $key): bool
    {
        $setting = $this->getByKey($key);
        
        if ($setting) {
            Cache::forget("setting.{$key}");
            Cache::forget("settings.group.{$setting->group}");
            return $setting->delete();
        }

        return false;
    }

    public function getGroups(): array
    {
        return Setting::distinct()->pluck('group')->toArray();
    }
}