<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description'
    ];

    protected $casts = [
        'value' => 'string', // We'll handle casting dynamically
    ];

    /**
     * Get setting value by key
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return $setting->getCastValue();
    }

    /**
     * Set setting value
     */
    public static function setValue(string $key, $value, string $group = 'general', string $type = 'string', string $description = null): bool
    {
        $setting = static::firstOrNew(['key' => $key]);
        
        $setting->fill([
            'value' => $value,
            'type' => $type,
            'group' => $group,
            'description' => $description
        ]);

        return $setting->save();
    }

    /**
     * Get casted value based on type
     */
    public function getCastValue()
    {
        return match($this->type) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'json' => json_decode($this->value, true),
            'text' => $this->value,
            default => $this->value,
        };
    }

    /**
     * Set value with proper type casting
     */
    public function setValueAttribute($value)
    {
        if ($this->type === 'json' && is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } elseif ($this->type === 'boolean') {
            $this->attributes['value'] = $value ? '1' : '0';
        } else {
            $this->attributes['value'] = (string) $value;
        }
    }

    /**
     * Get settings by group
     */
    public static function getByGroup(string $group): array
    {
        return static::where('group', $group)
            ->get()
            ->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->getCastValue()];
            })
            ->toArray();
    }

    /**
     * Get all settings as array
     */
    public static function getAllSettings(): array
    {
        return static::all()
            ->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->getCastValue()];
            })
            ->toArray();
    }
}