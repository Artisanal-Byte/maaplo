<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPhoto;
use App\Models\Measurement;
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
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $plan = $user->subscriptionPlan;

        $customerCount = $user->customers()->count();
        $customerLimitExceeded = $plan
            ? $customerCount >= $plan->user_limit
            : $customerCount >= 5;

        $query = $user->customers()
            ->with(['photos' => fn($q) => $q->where('label', 'Faceimage'), 'orders'])
            ->orderBy('name', 'asc');

        // ✅ Apply search filter here
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $paginatedCustomers = $query->paginate(10)->withQueryString(); // Keep query in pagination links

        $paginatedCustomers->getCollection()->transform(function ($c) {
            $total_payment = $c->orders->sum('total_amount');
            $advance_payment = $c->orders->sum('advance_paid');
            $payment_due = $total_payment - $advance_payment;

            return [
                'id' => $c->id,
                'name' => $c->name,
                'email' => $c->email,
                'country_code' => $c->country_code,
                'phone' => $c->phone,
                'gender' => $c->gender,
                'dob' => $c->dob,
                'active_orders' => $c->active_orders ?? null,
                'total_payment' => number_format($total_payment, 2),
                'advance_payment' => number_format($advance_payment, 2),
                'payment_due' => number_format($payment_due, 2),
                'face_image' => optional($c->photos->first())->image_url
                    ? asset($c->photos->first()->image_url)
                    : null,
            ];
        });

        return Inertia::render('customer/Index', [
            'customers' => $paginatedCustomers,
            'customer_limit_exceeded' => $customerLimitExceeded,
            'plan_title' => $plan ? $plan->plan_title : 'Free',
            'plan_limit' => $plan ? $plan->user_limit : 5,
            'search' => $search,
        ]);
    }



    // Show the form for creating a new customer
    public function create()
    {
        $user = auth()->user();
        $customerCount = $user->customers()->count();
        $customerLimitExceeded = $user->subscription_plan === 'free' && $customerCount >= 5;

        $user_id = auth()->id();
        $measurements = Measurement::all();
        $setData = [];

        foreach ($measurements as $measurement) {
            $setData[$measurement->slug] = null;
        }
        return Inertia::render('customer/Create', [
            'user_id' => $user_id,
            'measurements' => json_encode($setData, true),
            'customer_limit_exceeded' => $customerLimitExceeded,
            'toAsk' => json_encode($setData, true),
            'notes' => $customer->notes ?? [],
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
            'country_code' => ['nullable', 'string', 'regex:/^\+\d{1,4}$/'],
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'email' => 'nullable|email|unique:customers,email',
            'address' => 'required|string|max:255',
            'measurements' => 'nullable|array',
            'dob' => 'nullable|date',
            'notes' => 'nullable|array',
            'half_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'full_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        try {
            DB::beginTransaction();
            $username = preg_replace('/\s+/', '_', strtolower($user->name));
            $customerName = preg_replace('/\s+/', '_', strtolower($validated['name']));
            $address = $validated['address'];
            $addressJson = json_encode(['value' => $address]);
            // First, create the customer to get the customer ID
            $customer = Customer::create([
                // 'user_id' => $user_id,
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'country_code' => $validated['country_code'],
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

        $measurements = Measurement::all();
        $setData = [];
        foreach ($measurements as $measurement) {
            $setData[$measurement->slug] = null;
        }

        $hasOrder = $customer->orders()->exists();
        return Inertia::render('customer/Edit', [
            'customer' => array_merge(
                $customer->toArray(),
                ['gender' => $customer->getAttributes()['gender']],
                [
                    'face_image' => $faceImage ? $faceImage->image_url : null,
                    'full_body_image' => $fullBodyImage ? $fullBodyImage->image_url : null,
                    'measurements' => $customer->base_measurements,
                ]
            ),
            'isOrder' => $hasOrder,
            'toAsk' => json_encode($setData, true),
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
            'country_code' => ['nullable', 'string', 'regex:/^\+\d{1,4}$/'],
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'gender' => 'required|in:m,f,o',
            'dob' => 'nullable|date',
            'measurements' => 'nullable|array',
            'address' => 'required|string',
            'notes' => 'nullable|array',
            'half_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'full_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_half_image' => 'nullable|boolean',
            'remove_full_image' => 'nullable|boolean',
        ]);

        try {
            // Update base customer fields
            $customer->update([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'gender' => $validated['gender'],
                'dob' => $validated['dob'],
                'base_measurements' => $validated['measurements'] ?? [],
                'country_code' => $validated['country_code'],
                'phone' => $validated['phone'],
                'notes' => json_encode($validated['notes']),
                'address' => json_encode(['value' => $validated['address']]),
            ]);

            // Handle image deletions first
            if ($request->boolean('remove_half_image')) {
                $oldHalfImage = CustomerPhoto::where('customer_id', $customer->id)
                    ->where('label', 'Faceimage')
                    ->first();

                if ($oldHalfImage) {
                    $relativePath = Str::after($oldHalfImage->image_url, 'storage/');
                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }
                    $oldHalfImage->delete();
                }
            }

            if ($request->boolean('remove_full_image')) {
                $oldFullImage = CustomerPhoto::where('customer_id', $customer->id)
                    ->where('label', 'Fullbody')
                    ->first();

                if ($oldFullImage) {
                    $relativePath = Str::after($oldFullImage->image_url, 'storage/');
                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }
                    $oldFullImage->delete();
                }
            }

            // Handle image uploads
            $user = auth()->user();
            $username = preg_replace('/\s+/', '_', strtolower($user->name));
            $customerName = preg_replace('/\s+/', '_', strtolower($validated['name']));

            if ($request->hasFile('half_image')) {
                // Delete previous face image if exists
                $oldHalfImage = CustomerPhoto::where('customer_id', $customer->id)
                    ->where('label', 'Faceimage')
                    ->first();

                if ($oldHalfImage) {
                    $relativePath = Str::after($oldHalfImage->image_url, 'storage/');
                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }
                }

                // Store new face image
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
                // Delete previous full image if exists
                $oldFullImage = CustomerPhoto::where('customer_id', $customer->id)
                    ->where('label', 'Fullbody')
                    ->first();

                if ($oldFullImage) {
                    $relativePath = Str::after($oldFullImage->image_url, 'storage/');
                    if (Storage::disk('public')->exists($relativePath)) {
                        Storage::disk('public')->delete($relativePath);
                    }
                }

                // Store new full image
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
}
