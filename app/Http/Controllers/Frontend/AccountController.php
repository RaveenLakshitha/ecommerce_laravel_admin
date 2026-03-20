<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display the customer account dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Load relations for the user (addresses and latest 10 orders)
        $user->load(['addresses', 'orders' => function ($query) {
            $query->latest()->take(10);
        }]);
        $customer = $user->customer;

        // If for some reason a user doesn't have a customer profile, create an empty one or handle it gracefully
        if (!$customer) {
            $customer = $user->customer()->create([
                'first_name' => explode(' ', $user->name)[0] ?? 'User',
                'last_name' => explode(' ', $user->name)[1] ?? '',
                'email' => $user->email,
            ]);
        }

        $activeTab = request()->query('tab', 'dashboard');

        // Prepare stats for the summary
        $totalOrders = $user->orders->count();
        $wishlistItems = 0; // Placeholder if you implement Wishlist
        $pendingReturns = 0; // Placeholder if you implement Returns

        // Separate orders by status
        $allOrders = $user->orders;
        $activeOrders = $allOrders->filter(function ($order) {
            return !in_array(strtolower($order->status), ['delivered', 'cancelled', 'returned']);
        });

        return view('frontend.account.layout', compact(
            'user',
            'customer',
            'activeTab',
            'totalOrders',
            'wishlistItems',
            'pendingReturns',
            'allOrders',
            'activeOrders'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $user->update([
            'name' => trim($request->first_name . ' ' . $request->last_name),
            'email' => $request->email,
        ]);

        if ($customer) {
            $customer->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'gender' => $request->gender,
            ]);
        }

        return redirect()->route('account.dashboard', ['tab' => 'profile'])->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return redirect()->route('account.dashboard', ['tab' => 'profile'])->with('success', 'Password updated successfully.');
    }

    public function storeAddress(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'type' => 'required|in:shipping,billing,both',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:50',
            'country' => 'required|string|max:255',
        ]);

        $isDefault = $request->has('is_default');

        // If this is set as default, unset others first
        if ($isDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            'type' => $request->type,
            'is_default' => $isDefault,
            'first_name' => $user->customer->first_name ?? $user->name,
            'last_name' => $user->customer->last_name ?? '',
            'phone' => $user->customer->phone ?? '',
            'address_line1' => $request->address_line_1,
            'address_line2' => $request->address_line_2,
            'city' => $request->city,
            'province' => $request->state,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
        ]);

        return redirect()->route('account.dashboard', ['tab' => 'addresses'])->with('success', 'Address added successfully.');
    }

    public function destroyAddress(\App\Models\Address $address)
    {
        $user = Auth::user();
        if ($address->user_id == $user->id) {
            $address->delete();
            return redirect()->route('account.dashboard', ['tab' => 'addresses'])->with('success', 'Address removed.');
        }

        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    public function setDefaultAddress(\App\Models\Address $address)
    {
        $user = Auth::user();
        if ($address->user_id == $user->id) {
            // Unset previous default
            $user->addresses()->update(['is_default' => false]);
            // Set new default
            $address->update(['is_default' => true]);
            
            return redirect()->route('account.dashboard', ['tab' => 'addresses'])->with('success', 'Default address updated.');
        }

        return redirect()->back()->with('error', 'Unauthorized action.');
    }
}
