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
        $organization = auth()->user()->organization;

        $organizations = $organization ? [$organization] : []; // Avoid sending [null]

        return Inertia::render('organization/Index', compact('organizations'));
    }

    public function create()
    {
        return Inertia::render('organization/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_name' => 'required|string',
            'account_holder_name' => 'required|string',
            'account_number' => 'required|string',
            'ifsc_code' => 'required|string',
            'branch_name' => 'required|string',
            'bank_name' => 'required|string',
            'state' => 'required|string',
            'qr_payment_img' => 'nullable|file|max:5120',
            'organization_logo' => 'nullable|file|max:5120',
            'gst_number' => 'nullable|string',
            'address' => 'required|string',
            'logo_request' => 'nullable|boolean',
        ]);
        // dd($data);
        try {
            DB::beginTransaction();

            $organization = Organization::create([
                'organization_name' => $data['organization_name'],
                'gst_number' => $data['gst_number'],
                'address' => $data['address'],
                'account_holder_name' => $data['account_holder_name'],
                'account_number' => $data['account_number'],
                'ifsc_code' => $data['ifsc_code'],
                'branch_name' => $data['branch_name'],
                'bank_name' => $data['bank_name'],
                'state' => $data['state'],
                'logo_request' => $data['logo_request'] ?? false,
                'logo_created' => false,
                'user_id' => auth()->id(),
            ]);

            if ($request->hasFile('organization_logo')) {
                $file = $request->file('organization_logo');
                $username = Str::slug($data['organization_name'] ?? 'organization');
                $customerName = $username;

                $orgLogoPath = ImageHelper::imageProccess($file, $organization->id, $username, $organization->id, $customerName, 'org_logo');
                $organization->organization_logo = $orgLogoPath;
                $organization->save();
            }

            if ($request->hasFile('qr_payment_img')) {
                $file = $request->file('qr_payment_img');
                $username = Str::slug($data['organization_name'] ?? 'organization');
                $customerName = $username;
                $qrPaymentPath = ImageHelper::imageProccess($file, $organization->id, $username, $organization->id, $customerName, 'qr_payment_img');
                $organization->qr_payment_img = $qrPaymentPath;
                $organization->save();
            }

            $user = auth()->user();
            $user->organization_id = $organization->id;
            $user->hash_organization = false;
            $user->save();

            DB::commit();
            ToastMagic::success('Organization Updated successfully!');
            return redirect()->route('organization.index')->with('success', 'Organization created and assigned.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Creation failed: ' . $e->getMessage());
        }
    }

    public function edit(Organization $organization)
    {
        return Inertia::render('organization/Edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'organization_name' => 'required|string',
            'organization_logo' => 'nullable|file|max:5120',
            'gst_number' => 'nullable|string|max:15',
            'address' => 'required|string',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code' => 'required|string|max:15',
            'branch_name' => 'required|string|max:100',
            'bank_name' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'qr_payment_img' => 'nullable|file|image|max:5120',
        ]);
        try {
            DB::beginTransaction();

            if ($request->hasFile('organization_logo')) {
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

            // Handle QR payment image upload
            if ($request->hasFile('qr_payment_img')) {
                if ($organization->qr_payment_img) {
                    $oldQrPath = Str::after($organization->qr_payment_img, 'storage/');
                    if (Storage::disk('public')->exists($oldQrPath)) {
                        Storage::disk('public')->delete($oldQrPath);
                    }
                }

                $file = $request->file('qr_payment_img');
                // You can reuse your ImageHelper or write logic to store the QR image:
                $qrImagePath = $file->store('qr_payment_images', 'public'); // example path
                $data['qr_payment_img'] = 'storage/' . $qrImagePath;
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
