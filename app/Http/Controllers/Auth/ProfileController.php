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
        return Inertia::render('profile/Show', ['user' => $user]);
    }
    public function edit()
    {
        $user = Auth::user();
        return Inertia::render('profile/Edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'organization_name' => 'nullable|string|max:255',
            'organization_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'subscription_plan' => 'required|string|max:50',
            'validity' => 'nullable|date',
        ]);

        // Handle organization logo upload
        if ($request->hasFile('organization_logo')) {
            // Delete old logo if exists
            if ($user->organization_logo) {
                Storage::disk('public')->delete($user->organization_logo);
            }

            // Store new logo
            $path = $request->file('organization_logo')->store('organization-logos', 'public');
            $validated['organization_logo'] = $path;
        } else {
            // Keep the old organization logo path if no new file uploaded
            $validated['organization_logo'] = $user->organization_logo;
        }

        if (isset($validated['validity'])) {
            $validated['validity'] = Carbon::parse($validated['validity'])->format('Y-m-d');
        }

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully');
    }
}
