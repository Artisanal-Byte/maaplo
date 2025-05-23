<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
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
        return Inertia::render('admin/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'subscription_plan' => 'required|string|max:255',
            'validity' => 'required|date',
            'password' => 'required|string|min:6',
            'organization_logo' => 'nullable|file|image|max:2048',
            'status' => 'boolean',
        ]);
        $validated['password'] = bcrypt($validated['password']);
        try {
            DB::beginTransaction();
            // temporary store user
            $tempUser = User::create([
                ...$validated,
                'organization_logo' => '',
            ]);
            // If logo image exists, process and update
            if ($request->hasFile('organization_logo')) {
                $file = $request->file('organization_logo');
                $username = Str::slug($validated['name']);
                $userId = $tempUser->id;
                $customerName = $username;
                $customerId = $userId;

                $path = ImageHelper::imageProccess($file, $customerId, $username, $userId, $customerName, 'org_logo');
                $tempUser->update(['organization_logo' => $path]);
            }
            $userFind = User::find($tempUser->id);
            $userFind->update(['id' => $tempUser->id], $validated);

            DB::commit();
            ToastMagic::success('User created successfully!');
            return redirect()->route('user.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
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
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'organization_name' => 'nullable|string|max:255',
            'subscription_plan' => 'nullable|string|max:255',
            'validity' => 'nullable|date',
            'password' => 'nullable|string|min:6',
            'organization_logo' => 'nullable|max:2048',
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
                if ($user->organization_logo) {
                    // Extract relative path from URL or full path (e.g., 'storage/org_logos/logo.png')
                    $relativePath = Str::after($user->organization_logo, 'storage/');

                    // Check and delete the file if it exists
                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }
                }
                // Process and save new logo
                $file = $request->file('organization_logo');
                $username = Str::slug($validated['name']);
                $userId = $user->id;
                $customerName = $username;

                $path = ImageHelper::imageProccess($file, $userId, $username, $userId, $customerName, 'org_logo');
                $validated['organization_logo'] = $path;
            }

            $user->update($validated);

            DB::commit();
            ToastMagic::success('User Updated successfully!');
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
