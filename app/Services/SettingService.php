<?php

namespace App\Services;

use App\Interfaces\SettingRepositoryInterface;
use App\Helpers\ActivityHelper;

class SettingService
{
    protected $settingRepository;

    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function getAllSettings()
    {
        return $this->settingRepository->getAll();
    }

    public function getSettingValue(string $key, $default = null)
    {
        return $this->settingRepository->getValue($key, $default);
    }

    public function updateSettings(array $settings, string $group): bool
    {
        $updated = $this->settingRepository->updateMultiple($settings);
        
        if ($updated) {
            ActivityHelper::log('updated', 'settings', "Updated {$group} settings");
        }

        return $updated;
    }

    public function getSettingsByGroup(string $group): array
    {
        return $this->settingRepository->getByGroup($group);
    }

    public function getAvailableGroups(): array
    {
        return $this->settingRepository->getGroups();
    }

    /**
     * Get all settings grouped by category
     */
    public function getAllGroupedSettings(): array
    {
        $groups = $this->getAvailableGroups();
        $settings = [];

        foreach ($groups as $group) {
            $settings[$group] = $this->getSettingsByGroup($group);
        }

        return $settings;
    }

    /**
     * Get specific setting groups for admin
     */
    public function getAdminSettings(): array
    {
        return [
            'general' => $this->getSettingsByGroup('general'),
            'ecommerce' => $this->getSettingsByGroup('ecommerce'),
            'vendor' => $this->getSettingsByGroup('vendor'),
            'order' => $this->getSettingsByGroup('order'),
            'payment' => $this->getSettingsByGroup('payment'),
            'tax' => $this->getSettingsByGroup('tax'),
            'email' => $this->getSettingsByGroup('email'),
        ];
    }

    /**
     * Get frontend settings (publicly accessible)
     */
    public function getFrontendSettings(): array
    {
        return [
            'site_name' => $this->getSettingValue('site_name'),
            'site_currency' => $this->getSettingValue('site_currency'),
            'currency_symbol' => $this->getSettingValue('currency_symbol'),
            'currency_position' => $this->getSettingValue('currency_position'),
            'free_shipping_min_amount' => $this->getSettingValue('free_shipping_min_amount'),
            'tax_rate' => $this->getSettingValue('tax_rate'),
        ];
    }

    /**
     * Check if maintenance mode is enabled
     */
    public function isMaintenanceMode(): bool
    {
        return (bool) $this->getSettingValue('maintenance_mode', false);
    }

    /**
     * Get vendor commission rate
     */
    public function getVendorCommissionRate(): float
    {
        return (float) $this->getSettingValue('vendor_commission_rate', 10.00);
    }

    /**
     * Get tax rate
     */
    public function getTaxRate(): float
    {
        return (float) $this->getSettingValue('tax_rate', 18.00);
    }
}