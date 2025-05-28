<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::all();
        return Inertia::render('organization/Index', compact('organizations'));
    }

    public function create()
    {
        return Inertia::render('organization/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_name' => 'nullable|string',
            'organization_logo' => 'nullable|file|max:5120',
            'gst_number' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Step 1: Create organization without logo
            $organization = Organization::create([
                'organization_name' => $data['organization_name'],
                'gst_number' => $data['gst_number'],
                'address' => $data['address'],
            ]);

            // Step 2: Handle logo upload
            if ($request->hasFile('organization_logo')) {
                $file = $request->file('organization_logo');
                $username = Str::slug($data['organization_name'] ?? 'organization');
                $customerName = $username;

                $orgLogoPath = ImageHelper::imageProccess($file, $organization->id, $username, $organization->id, $customerName, 'org_logo');
                $organization->organization_logo = $orgLogoPath;
                $organization->save();
            }

            // Step 3: Assign organization_id to current user
            $user = auth()->user();
            $user->organization_id = $organization->id;
            $user->hash_organization = false;
            $user->save();

            DB::commit();

            return redirect()->route('organization.index')->with('success', 'Organization created and assigned.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Creation failed: ' . $e->getMessage());
        }
    }


    // public function edit(Organization $id)
    // {
    //     $organization = Organization::findOrFail($id);
    //     if (!$organization) {
    //         return redirect()->route('organization.index')->with('error', 'Organization not found.');
    //     }

    //     // Pass the organization to the Inertia view
    //     // return $this->renderEditView($organization);
    //      return Inertia::render('organization/Edit', compact('organization'));
    // }

    public function edit(Organization $organization)
    {
        // dd($organization);
        return Inertia::render('organization/Edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'organization_name' => 'nullable|string',
            'organization_logo' => 'nullable|file|max:5120', // Note: use 'file' if you're uploading a file
            'gst_number' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('organization_logo')) {
                // Delete old organization logo
                if ($organization->organization_logo) {
                    $orgRelativePath = Str::after($organization->organization_logo, 'storage/');
                    if (Storage::disk('public')->exists($orgRelativePath)) {
                        Storage::disk('public')->delete($orgRelativePath);
                    }
                }

                // Save new logo
                $file = $request->file('organization_logo');
                $username = Str::slug($organization->organization_name ?? 'organization');
                $userId = $organization->id;
                $customerName = $username;

                $orgLogoPath = ImageHelper::imageProccess($file, $userId, $username, $userId, $customerName, 'org_logo');
                $data['organization_logo'] = $orgLogoPath;
            }

            $organization->update($data);

            DB::commit();
            ToastMagic::success('Organization updated successfully!');
            return redirect()->route('organization.index')->with('success', 'Organization updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return redirect()->route('organization.index')->with('success', 'Organization deleted.');
    }

    public function setNoOrganization(Request $request)
    {
        $user = auth()->user();

        // Update the user's hash_organization column to false
        $user->update(['hash_organization' => false]);

        // Redirect back to the dashboard with a success message
        return redirect()->route('dashboard')->with('success', 'Organization skipped.');
    }
}
