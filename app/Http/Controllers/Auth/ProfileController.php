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
// dd( $request->all() );
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'password' => 'nullable|string|min:8',
            'organization_name' => 'nullable|string|max:255',
            'organization_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        try {
            DB::beginTransaction();

            $username = Str::slug($validated['name']);
            $userId = $user->id;

            // === Handle Organization Logo ===
            if ($request->hasFile('organization_logo')) {
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

                $orgFile = $request->file('organization_logo');
                $orgLogoPath = ImageHelper::imageProccess($orgFile, $userId, $username, $userId, $username, 'org_logo');
                $thumbnailPath = ImageHelper::saveThumbnail($orgFile, $userId, $username, $userId, $username, 'thumbnail_logo');

                $validated['organization_logo'] = $orgLogoPath;
                $validated['thumbnail_logo'] = $thumbnailPath;
            } else {
                $validated['organization_logo'] = $user->organization_logo;
                $validated['thumbnail_logo'] = $user->thumbnail_logo;
            }

            // === Handle Avatar ===
            if ($request->hasFile('avatar')) {
                // Delete current avatar
                if ($user->avatar) {
                    $avatarRelativePath = Str::after($user->avatar, 'storage/');
                    if (Storage::disk('public')->exists($avatarRelativePath)) {
                        Storage::disk('public')->delete($avatarRelativePath);
                    }
                }

                // Delete old avatar_* files in the folder
                $oldFiles = Storage::disk('public')->files("users/useravatar/{$username}_{$userId}");
                foreach ($oldFiles as $file) {
                    if (Str::contains($file, 'avatar_')) {
                        Storage::disk('public')->delete($file);
                    }
                }

                $avatarFile = $request->file('avatar');
                // dd($avatarFile );
                $validated['avatar'] = ImageHelper::imageAvatar($avatarFile, $username, $userId);
            } else {
                $validated['avatar'] = $user->avatar;
            }

            // dd($user->avatar, $validated['avatar']); // You can uncomment for debug
            $user->update($validated);

            DB::commit();

            return redirect()->route('profile.show')->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'There was an error updating the profile: ' . $e->getMessage());
        }
    }
}
