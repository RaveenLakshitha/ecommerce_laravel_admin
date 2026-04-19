<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGatewaySetting;

class PaymentGatewaySettingController extends Controller
{
    /**
     * Settings page for configuring payment gateways.
     */
    public function index()
    {
        // Fetch or create default gateway records to show in the form
        $gateways = ['stripe', 'paypal', 'payhere', 'cod', 'bank'];
        $settings = [];

        foreach ($gateways as $gw) {
            $settings[$gw] = PaymentGatewaySetting::firstOrCreate(
                ['gateway' => $gw],
                [
                    'display_name' => ucfirst($gw),
                    'is_active' => ($gw === 'cod'), // enable COD by default
                    'environment' => 'sandbox',
                ]
            );
        }

        return view('admin.settings.payment_gateways', compact('settings'));
    }

    /**
     * Updates settings for a specific gateway.
     */
    public function update(Request $request, string $gateway)
    {
        $setting = PaymentGatewaySetting::where('gateway', $gateway)->firstOrFail();

        $data = $request->validate([
            'is_active' => 'nullable|boolean',
            'environment' => 'required|in:sandbox,live',
            'public_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'merchant_id' => 'nullable|string',
            'display_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $data['is_active'] = $request->has('is_active');

        $setting->update($data);

        return back()->with('success', ucfirst($gateway) . ' settings have been updated successfully.');
    }
}
