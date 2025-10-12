<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Setting;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $localDeliveryCharge = (float) Setting::get('local_delivery_charge', 0);
        return Inertia::render('Admin/Settings/Index', [
            'local_delivery_charge' => $localDeliveryCharge,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'local_delivery_charge' => 'required|numeric|min:0',
        ]);

        Setting::set('local_delivery_charge', $data['local_delivery_charge']);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings saved successfully.');
    }
}
