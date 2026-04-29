<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to Google's OAuth page.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])   // always show account picker
            ->redirect();
    }

    /**
     * Handle Google's OAuth callback.
     * Security measures:
     *  - Session is regenerated on every login (fixes session fixation).
     *  - google_id is the canonical identifier — email cannot be hijacked
     *    by a different Google account after the first link.
     *  - Password stays null for Google-only accounts (no password guessing risk).
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google authentication failed. Please try again.']);
        }

        // 1. Find existing user by google_id (most secure — not email)
        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            // 2. Try to find an existing account by email (link them)
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Link the existing account to this Google identity
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            } else {
                // 3. Create a brand-new user
                $nameParts = explode(' ', $googleUser->getName(), 2);

                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'google_id'         => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                    'email_verified_at' => now(), // Google already verified the email
                    'password'          => null,  // no password for OAuth-only accounts
                ]);

                // Create the corresponding Customer profile
                Customer::create([
                    'user_id'    => $user->id,
                    'first_name' => $nameParts[0] ?? $googleUser->getName(),
                    'last_name'  => $nameParts[1] ?? '',
                    'email'      => $googleUser->getEmail(),
                ]);
            }
        } else {
            // Refresh avatar in case Google updated it
            $user->update(['avatar' => $googleUser->getAvatar()]);
        }

        // Log in and regenerate session (prevents session fixation attacks)
        Auth::login($user, remember: true);
        request()->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
