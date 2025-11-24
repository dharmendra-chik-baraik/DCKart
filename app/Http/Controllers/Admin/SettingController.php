<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingUpdateRequest;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        $settingsOverview = [
            'general' => [
                'count' => count($this->settingService->getSettingsByGroup('general')),
                'icon' => 'fas fa-cog',
                'color' => 'primary',
                'description' => 'Site name, currency, timezone, and basic configuration'
            ],
            'ecommerce' => [
                'count' => count($this->settingService->getSettingsByGroup('ecommerce')),
                'icon' => 'fas fa-shopping-cart',
                'color' => 'success',
                'description' => 'Currency format, stock settings, and ecommerce preferences'
            ],
            'vendor' => [
                'count' => count($this->settingService->getSettingsByGroup('vendor')),
                'icon' => 'fas fa-store',
                'color' => 'warning',
                'description' => 'Vendor registration, commission rates, and payout settings'
            ],
            'order' => [
                'count' => count($this->settingService->getSettingsByGroup('order')),
                'icon' => 'fas fa-shopping-bag',
                'color' => 'info',
                'description' => 'Order processing, cancellation, and shipping settings'
            ],
            'payment' => [
                'count' => count($this->settingService->getSettingsByGroup('payment')),
                'icon' => 'fas fa-credit-card',
                'color' => 'dark',
                'description' => 'Payment gateways and transaction settings'
            ],
            'tax' => [
                'count' => count($this->settingService->getSettingsByGroup('tax')),
                'icon' => 'fas fa-percentage',
                'color' => 'secondary',
                'description' => 'Tax rates and calculation methods'
            ],
            'email' => [
                'count' => count($this->settingService->getSettingsByGroup('email')),
                'icon' => 'fas fa-envelope',
                'color' => 'danger',
                'description' => 'Email configuration and notification settings'
            ],
        ];

        $maintenanceMode = $this->settingService->isMaintenanceMode();
        
        return view('admin.settings.index', compact('settingsOverview', 'maintenanceMode'));
    }

    public function general()
    {
        $settings = $this->settingService->getAdminSettings();
        $groups = $this->settingService->getAvailableGroups();
        
        return view('admin.settings.general', compact('settings', 'groups'));
    }

    public function updateGeneral(SettingUpdateRequest $request)
    {
        try {
            $validated = $request->validated();
            
            $updated = $this->settingService->updateSettings($validated, 'general');
            
            if ($updated) {
                return redirect()->route('admin.settings.general')
                    ->with('success', 'General settings updated successfully.');
            }
            
            return redirect()->back()
                ->with('error', 'Failed to update settings.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    public function email()
    {
        $emailSettings = $this->settingService->getSettingsByGroup('email');
        return view('admin.settings.email', compact('emailSettings'));
    }

    public function updateEmail(Request $request)
    {
        try {
            $validated = $request->validate([
                'mail_from_address' => 'required|email|max:255',
                'mail_from_name' => 'required|string|max:255',
                'customer_order_notifications' => 'boolean',
                'vendor_order_notifications' => 'boolean',
            ]);
            
            $updated = $this->settingService->updateSettings($validated, 'email');
            
            if ($updated) {
                return redirect()->route('admin.settings.email')
                    ->with('success', 'Email settings updated successfully.');
            }
            
            return redirect()->back()
                ->with('error', 'Failed to update email settings.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating email settings: ' . $e->getMessage());
        }
    }

    public function payment()
    {
        $paymentSettings = $this->settingService->getSettingsByGroup('payment');
        return view('admin.settings.payment', compact('paymentSettings'));
    }

    public function updatePayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_gateways' => 'required|array',
                'default_payment_method' => 'required|string|max:50',
                'payment_test_mode' => 'boolean',
            ]);
            
            // Convert array to JSON for storage
            if (isset($validated['payment_gateways'])) {
                $validated['payment_gateways'] = json_encode($validated['payment_gateways']);
            }
            
            $updated = $this->settingService->updateSettings($validated, 'payment');
            
            if ($updated) {
                return redirect()->route('admin.settings.payment')
                    ->with('success', 'Payment settings updated successfully.');
            }
            
            return redirect()->back()
                ->with('error', 'Failed to update payment settings.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating payment settings: ' . $e->getMessage());
        }
    }

    public function maintenance()
    {
        $maintenanceMode = $this->settingService->isMaintenanceMode();
        return view('admin.settings.maintenance', compact('maintenanceMode'));
    }

    public function toggleMaintenance(Request $request)
    {
        try {
            $maintenanceMode = $request->input('maintenance_mode', false);
            
            $updated = $this->settingService->updateSettings([
                'maintenance_mode' => $maintenanceMode
            ], 'general');
            
            if ($updated) {
                $status = $maintenanceMode ? 'enabled' : 'disabled';
                return redirect()->route('admin.settings.maintenance')
                    ->with('success', "Maintenance mode {$status} successfully.");
            }
            
            return redirect()->back()
                ->with('error', 'Failed to update maintenance mode.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating maintenance mode: ' . $e->getMessage());
        }
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            
            ActivityHelper::log('updated', 'settings', 'Cleared application cache');
            
            return redirect()->back()
                ->with('success', 'Application cache cleared successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error clearing cache: ' . $e->getMessage());
        }
    }
}