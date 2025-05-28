<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

    public function edit(Organization $id)
    {
        $organization = Organization::findOrFail($id);
        if (!$organization) {
            return redirect()->route('organization.index')->with('error', 'Organization not found.');
        }

        // Pass the organization to the Inertia view
        // return $this->renderEditView($organization);
         return Inertia::render('organization/Edit', compact('organization'));
    }


    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'organization_name' => 'nullable|string',
            'organization_logo' => 'nullable|string',
            'gst_number' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $organization->update($data);
        return redirect()->route('organization.index')->with('success', 'Organization updated.');
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        return redirect()->route('organization.index')->with('success', 'Organization deleted.');
    }
}
