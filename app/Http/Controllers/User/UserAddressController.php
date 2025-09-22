<?php

namespace App\Http\Controllers\User;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class UserAddressController extends Controller
{
    /**
     * Show the form for creating a new address.
     */
    public function create()
    {
        return Inertia::render('User/Address/Create');
    }

    /**
     * Store a newly created address in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        // Map district to state field for database storage
        $validated['state'] = $validated['district'];
        unset($validated['district']);
        
        $validated['user_id'] = auth()->id();

        UserAddress::create($validated);

        return redirect()->route('checkout.index')->with('success', 'Address added successfully!');
    }
}
