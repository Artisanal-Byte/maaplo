<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Helpers\ImageHelper;
use App\Models\UserCustomer;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    // Display a listing of the customers
    public function index()
    {
        $customers = Auth::user()->customers()->with(['photos' => fn($q) => $q->where('label', 'Faceimage')])
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'email' => $c->email,
                'phone' => $c->phone,
                'gender' => $c->gender,
                'dob' => $c->dob,
                'active_orders' => $c->active_orders ?? null,
                'payment_due' => $c->payment_due ?? null,
                'face_image' => optional($c->photos->first())->image_url ? asset($c->photos->first()->image_url) : null,
            ]);

        return Inertia::render('customer/Index', [
            'customers' => $customers,
        ]);
    }

    // Show the form for creating a new customer
    public function create()
    {
        $user = auth()->user();
        $customerCount = Customer::where('user_id', $user->id)->count();
        $customerLimitExceeded = $user->subscription_plan === 'free' && $customerCount >= 5;

        $user_id = auth()->id();
        return Inertia::render('customer/Create', [
            'user_id' => $user_id,
            'customer_limit_exceeded' => $customerLimitExceeded,
        ]);
    }

    // Store a newly created customer in storage
    public function store(Request $request)
    {
        $user_id = auth()->id();
        $user = User::findOrFail($user_id);
        if ($request->has('notes') && is_string($request->notes)) {
            $request->merge([
                'notes' => json_decode($request->notes, true),
            ]);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:m,f,o',
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'email' => 'nullable|email|unique:customers,email',
            'address' => 'required|string|max:255',
            'measurements' => 'nullable|array',
            'dob' => 'nullable|date',
            'notes' => 'nullable|array',
            'half_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'full_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        try {
            DB::beginTransaction();

            $username = preg_replace('/\s+/', '_', strtolower($user->name));
            $customerName = preg_replace('/\s+/', '_', strtolower($validated['name']));
            $address = $validated['address'];
            $addressJson = json_encode(['value' => $address]);

            // First, create the customer to get the customer ID
            $customer = Customer::create([
                'user_id' => $user_id,
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'base_measurements' => json_encode($validated['measurements']),
                'dob' => $validated['dob'],
                'address' => $addressJson,
                'notes' => json_encode($validated['notes']),
            ]);

            //Store Customer id and user id in the pivot table
            $customerId = $customer->id;
            UserCustomer::create([
                'user_id' => $user_id,
                'customer_id' => $customerId,
            ]);
            // Handle the half image if it exists
            $halfImagePath = null;
            if ($request->hasFile('half_image')) {
                $halfImagePath = ImageHelper::imageProccess(
                    $request->file('half_image'),
                    $customerId,
                    $username,
                    $user_id,
                    $customerName,
                    'faceimage'
                );
            }

            // Handle the full image if it exists
            $fullImagePath = null;
            if ($request->hasFile('full_image')) {
                $fullImagePath = ImageHelper::imageProccess(
                    $request->file('full_image'),
                    $customerId,
                    $username,
                    $user_id,
                    $customerName,
                    'fullbody'
                );
            }

            // Save images to the database after the customer is created
            if ($halfImagePath) {
                CustomerPhoto::create([
                    'customer_id' => $customer->id,
                    'image_url' => $halfImagePath,
                    'label' => 'Faceimage',
                ]);
            }

            if ($fullImagePath) {
                CustomerPhoto::create([
                    'customer_id' => $customer->id,
                    'image_url' => $fullImagePath,
                    'label' => 'Fullbody',
                ]);
            }

            DB::commit();
            ToastMagic::success('Customer created successfully!');
            return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    // Show the form for editing the specified customer
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        // Get the customer photos (face image and full-body image)
        $photos = CustomerPhoto::where('customer_id', $customer->id)->get();

        // Find the specific images
        $faceImage = $photos->where('label', 'Faceimage')->first();
        $fullBodyImage = $photos->where('label', 'Fullbody')->first();

        return Inertia::render('customer/Edit', [
            'customer' => array_merge(
                $customer->toArray(),
                ['gender' => $customer->getAttributes()['gender']],
                [
                    'face_image' => $faceImage ? $faceImage->image_url : null,
                    'full_body_image' => $fullBodyImage ? $fullBodyImage->image_url : null,
                    'measurements' => is_string($customer->base_measurements)
                        ? json_decode($customer->base_measurements, true)
                        : $customer->base_measurements,
                ]
            ),
            'notes' => $customer->notes ?? [],
        ]);
    }
    // Update the specified customer in storage
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $id,
            'phone' => 'required|max:10',
            'gender' => 'required|in:m,f,o',
            'dob' => 'nullable|date',
            'measurements' => 'nullable|array',
            'address' => 'required|string',
            'notes' => 'nullable|array',
            'half_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'full_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            // Update the base customer fields
            $customer->update([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'gender' => $validated['gender'],
                'dob' => $validated['dob'],
                'base_measurements' => json_encode($validated['measurements']),
                'phone' => $validated['phone'],
                'notes' => json_encode($validated['notes']),
                'address' => json_encode(['value' => $validated['address']]), // keep consistent format
            ]);

            // Handle images
            $user = auth()->user();
            $username = preg_replace('/\s+/', '_', strtolower($user->name));
            $customerName = preg_replace('/\s+/', '_', strtolower($validated['name']));

            if ($request->hasFile('half_image')) {
                // Delete previous half image
                $oldHalfImage = CustomerPhoto::where('customer_id', $customer->id)
                    ->where('label', 'Faceimage')
                    ->first();


                if ($oldHalfImage) {
                    $relativePath = Str::after($oldHalfImage->image_url, 'storage/');

                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }
                }

                // Store new image
                $halfImagePath = ImageHelper::imageProccess(
                    $request->file('half_image'),
                    $customer->id,
                    $username,
                    $user->id,
                    $customerName,
                    'faceimage'
                );

                CustomerPhoto::updateOrCreate(
                    ['customer_id' => $customer->id, 'label' => 'Faceimage'],
                    ['image_url' => $halfImagePath]
                );
            }

            if ($request->hasFile('full_image')) {
                // Delete previous full image
                $oldFullImage = CustomerPhoto::where('customer_id', $customer->id)
                    ->where('label', 'Fullbody')
                    ->first();


                if ($oldFullImage) {
                    $relativePath = Str::after($oldFullImage->image_url, 'storage/');

                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }
                }

                // Store new image
                $fullImagePath = ImageHelper::imageProccess(
                    $request->file('full_image'),
                    $customer->id,
                    $username,
                    $user->id,
                    $customerName,
                    'fullbody'
                );

                CustomerPhoto::updateOrCreate(
                    ['customer_id' => $customer->id, 'label' => 'Fullbody'],
                    ['image_url' => $fullImagePath]
                );
            }

            return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd('Customer update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'There was an error updating the customer: ' . $e->getMessage());
        }
    }

    // Remove the specified customer from storage
    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return redirect()->route('customers.index')->with('error', 'Customer not found!');
        }
        $customer->delete();
        return redirect()->route('customers.index')->with('status', 'Customer deleted successfully!');
    }


    // this delete function is used to delete the customer images from the database and storage
    //   public function destroy($id)
    // {
    //     $customer = Customer::find($id);

    //     if (!$customer) {
    //         return redirect()->route('customers.index')->with('error', 'Customer not found!');
    //     }

    //     try {
    //         DB::beginTransaction();

    //         // Delete associated photos from storage and DB
    //         $photos = CustomerPhoto::where('customer_id', $customer->id)->get();

    //         foreach ($photos as $photo) {
    //             $relativePath = Str::after($photo->image_url, 'storage/');
    //             if (Storage::disk('public')->exists($relativePath)) {
    //                 Storage::disk('public')->delete($relativePath);
    //             }
    //             $photo->delete(); // Delete DB record
    //         }

    //         // Delete pivot relationship
    //         UserCustomer::where('customer_id', $customer->id)->delete();

    //         // Finally delete customer
    //         $customer->delete();

    //         DB::commit();

    //         return redirect()->route('customers.index')->with('success', 'Customer permanently deleted.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->route('customers.index')->with('error', 'Failed to delete customer: ' . $e->getMessage());
    //     }
    // }
}
