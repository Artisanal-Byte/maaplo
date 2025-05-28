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
            'organization_logo' => 'nullable|string',
            'gst_number' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        Organization::create($data);
        return redirect()->route('organization.index')->with('success', 'Organization created.');
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
}
