<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Str;

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
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'password' => 'nullable|string|min:8',
            'organization_name' => 'nullable|string|max:255',
            'organization_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }
        try {
            DB::beginTransaction();

            if ($request->hasFile('organization_logo')) {
                // Delete old organization logo
                if ($user->organization_logo) {
                    $orgRelativePath = Str::after($user->organization_logo, 'storage/');
                    if (Storage::disk('public')->exists($orgRelativePath)) {
                        Storage::disk('public')->delete($orgRelativePath);
                    }
                }

                if ($user->thumbnail_logo) {
                    $thumbRelativePath = Str::after($user->thumbnail_logo, 'storage/');
                    if (Storage::disk('public')->exists($thumbRelativePath)) {
                        Storage::disk('public')->delete($thumbRelativePath);
                    }
                }

                // Process and save new logo
                $file = $request->file('organization_logo');
                $username = Str::slug($validated['name']);
                $userId = $user->id;
                $customerName = $username;

                $orgLogoPath = ImageHelper::imageProccess($file, $userId, $username, $userId, $customerName, 'org_logo');
                $thumbnailPath = ImageHelper::saveThumbnail($file, $userId, $username, $userId, $customerName, 'thumbnail_logo');

                $validated['organization_logo'] = $orgLogoPath;
                $validated['thumbnail_logo'] = $thumbnailPath;
            } else {
                // Keep existing logo if no new file uploaded
                $validated['organization_logo'] = $user->organization_logo;
                $validated['thumbnail_logo'] = $user->thumbnail_logo;
            }
            $user->update($validated);

            DB::commit();

            return redirect()->route('profile.show')->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'There was an error updating the profile: ' . $e->getMessage());
        }
    }
}
