<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        // dd($users);
        return Inertia::render('admin/Index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans = SubscriptionPlan::select('id', 'plan_title')->get();
        return Inertia::render('admin/Create', [
            'plans' => $plans,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'address' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'subscription_plan' => 'required|string|max:255',
            'validity' => 'required|date',
            'password' => 'required|string|min:8',
            'organization_logo' => 'nullable|file|image|max:5120',
            'status' => 'boolean',
        ]);
        // dd($validated);

        $validated['password'] = bcrypt($validated['password']);
        // dd( $validated['password']);
        try {
            DB::beginTransaction();

            // Create user with empty logo initially
            $tempUser = User::create([
                ...$validated,
                'organization_logo' => '',
                'thumbnail_logo' => '',
            ]);
            // dd($tempUser);
            // If logo image exists, process both original and thumbnail
            if ($request->hasFile('organization_logo')) {
                $file = $request->file('organization_logo');
                $username = Str::slug($validated['name']);
                $userId = $tempUser->id;
                $customerName = $username;
                $customerId = $userId;

                $orgLogoPath = ImageHelper::imageProccess($file, $customerId, $username, $userId, $customerName, 'org_logo');
                $thumbnailPath = ImageHelper::saveThumbnail($file, $customerId, $username, $userId, $customerName, 'thumbnail_logo');

                $tempUser->update([
                    'organization_logo' => $orgLogoPath,
                    'thumbnail_logo' => $thumbnailPath,
                ]);
            }

            DB::commit();
            ToastMagic::success('User created successfully!');
            return redirect()->route('user.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('User store failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        // dd($user->all());
        return Inertia::render('admin/Edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'address' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'subscription_plan' => 'required|string|in:free',
            'validity' => 'required|date',
            'password' => 'nullable|string|min:8',
            'organization_logo' => 'nullable|max:5120',
            'status' => 'boolean',
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

                // Delete old thumbnail logo
                if ($user->thumbnail_logo) {
                    $thumbRelativePath = Str::after($user->thumbnail_logo, 'storage/');
                    if (Storage::disk('public')->exists($thumbRelativePath)) {
                        Storage::disk('public')->delete($thumbRelativePath);
                    }
                }

                // Save new logos
                $file = $request->file('organization_logo');
                $username = Str::slug($validated['name']);
                $userId = $user->id;
                $customerName = $username;

                $orgLogoPath = ImageHelper::imageProccess($file, $userId, $username, $userId, $customerName, 'org_logo');
                $thumbnailPath = ImageHelper::saveThumbnail($file, $userId, $username, $userId, $customerName, 'thumbnail_logo');

                $validated['organization_logo'] = $orgLogoPath;
                $validated['thumbnail_logo'] = $thumbnailPath;
            }

            $user->update($validated);

            DB::commit();
            ToastMagic::success('User updated successfully!');
            return redirect()->route('user.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|boolean',
        ]);
        $user->status = $validated['status'];
        $user->save();
        return redirect()->back()->with('success', 'User status updated successfully.');
    }
}
