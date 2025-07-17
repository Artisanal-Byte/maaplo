<?php

namespace App\Http\Controllers;

use App\Helpers\PasswordHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{

    /**
     * Function : googleLogin
     * Description :  This Function Will Redirect To Google
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */

    public function googleLogin(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleAuthentication()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists
            $user = User::where('google_id', $googleUser->id)->first();

            // If user doesn't exist, create a new one
            if ($user) {
                // If user exists but doesn't have google_id, update it
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }
            } else {
                // Create new user if no user exists with that email
                $randomPassword = PasswordHelper::generatePassword();

                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make($randomPassword),
                    'google_id' => $googleUser->id
                ]);
            }

            // Now login the user (whether found or created)
            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }
}
