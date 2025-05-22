<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        // dd($request);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'organization_name' => 'nullable|string|max:255',
            'subscription_plan' => 'nullable|string|max:255',
            'validity' => 'nullable|date',
            'password' => 'required|string|min:6',
            'organization_logo' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);
// dd($validated);
        $validated['password'] = bcrypt($validated['password']);
// dd($validated);

        User::create($validated);

        return redirect()->route('user.index')->with('success', 'User created successfully.');
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
        // dd($request);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'organization_name' => 'nullable|string|max:255',
            'subscription_plan' => 'nullable|string|max:255',
            'validity' => 'nullable|date',
            'password' => 'nullable|string|min:6',
            'organization_logo' => 'nullable|string|max:255',
            'status' => 'boolean',
        ]);
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
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
