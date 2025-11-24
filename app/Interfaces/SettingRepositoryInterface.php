<?php

namespace App\Interfaces;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

interface SettingRepositoryInterface
{
    public function getAll(): Collection;
    public function getByKey(string $key): ?Setting;
    public function getValue(string $key, $default = null);
    public function setValue(string $key, $value, string $group = 'general', string $type = 'string', string $description = null): bool;
    public function getByGroup(string $group): array;
    public function updateMultiple(array $settings): bool;
    public function deleteByKey(string $key): bool;
    public function getGroups(): array;
}