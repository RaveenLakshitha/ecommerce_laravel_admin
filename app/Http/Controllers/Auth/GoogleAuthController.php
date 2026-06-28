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
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->redirectUrl(config('services.google.redirect'))
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(config('services.google.redirect'))
                ->user();
        } catch (Throwable $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Google authentication failed. Please try again.']);
        }
        $user = User::where('google_id', $googleUser->getId())->first();
        if (! $user) {
            $user = User::where('email', $googleUser->getEmail())->first();
            if ($user) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            } else {
                $nameParts = explode(' ', $googleUser->getName(), 2);
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'google_id'         => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                    'email_verified_at' => now(), 
                    'password'          => null,  
                ]);
                Customer::create([
                    'user_id'    => $user->id,
                    'first_name' => $nameParts[0] ?? $googleUser->getName(),
                    'last_name'  => $nameParts[1] ?? '',
                    'email'      => $googleUser->getEmail(),
                ]);
            }
        } else {
            $user->update(['avatar' => $googleUser->getAvatar()]);
        }
        Auth::login($user, remember: true);
        request()->session()->regenerate();
        \App\Http\Controllers\Frontend\CartController::mergeAfterLogin();
        return redirect()->intended(route('home'));
    }
}
