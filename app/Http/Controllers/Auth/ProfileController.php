<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return Inertia::render('profile/Show',['user'=>$user]);
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            // Personal Information
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Organization Fields
            'organization_name' => 'nullable|string|max:255',
            'organization_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'subscription_plan' => 'required|in:free,premium,enterprise',
            'validity' => 'nullable|date'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        // Handle organization logo upload
        if ($request->hasFile('organization_logo')) {
            // Delete old logo if exists
            if ($user->organization_logo) {
                Storage::delete($user->organization_logo);
            }
            
            $orgPath = $request->file('organization_logo')->store('organization-logos', 'public');
            $validated['organization_logo'] = $orgPath;
        }

        // Format validity date
        if ($request->has('validity')) {
            $validated['validity'] = Carbon::parse($validated['validity'])->format('Y-m-d');
        }

        $user->update($validated);

        return redirect()->route('profile.show')
                         ->with('success', 'Profile updated successfully');
    }
}