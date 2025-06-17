<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $subscriptionPlans = SubscriptionPlan::all();
        return Inertia::render('subscriptionplan/Index', [
            'subscriptionPlans' => $subscriptionPlans,
        ]);
    }

    public function create()
    {
        return Inertia::render('subscriptionplan/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_title' => 'required|string|max:255',
            'plan_description' => 'nullable|string',
            'plan_price' => 'required|numeric',
            'plan_currency' => 'required|string|in:INR,USD,EUR',
            'features' => 'required|array|min:1',
            'features.*' => 'required|string|min:1',
            'visibility' => 'required|boolean',
            'user_limit' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $formattedFeatures = [];
            foreach ($validated['features'] as $index => $value) {
                $formattedFeatures["feature" . ($index + 1)] = $value;
            }

            $validated['features'] = json_encode($formattedFeatures);

            SubscriptionPlan::create([
                'plan_title' => $validated['plan_title'],
                'plan_description' => $validated['plan_description'],
                'plan_price' => $validated['plan_price'],
                'plan_currency' => $validated['plan_currency'],
                'features' => $validated['features'],
                'visibility' => $validated['visibility'],
                'user_limit' => $validated['user_limit'],
            ]);

            DB::commit();

            ToastMagic::success('Plan created successfully!');
            return redirect()->route('subscription-plans.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Creation failed: ' . $e->getMessage());
        }
    }

    public function show(SubscriptionPlan $subscriptionPlan)
    {
        return response()->json($subscriptionPlan);
    }

    public function edit(SubscriptionPlan $subscription_plan)
    {
        // dd($subscription_plan->toArray());
        return Inertia::render('subscriptionplan/Edit', [
            'subscriptionPlan' => $subscription_plan,
        ]);
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $data = $request->validate([
            'plan_title' => 'required|string|max:255',
            'plan_description' => 'nullable|string',
            'plan_price' => 'required|numeric',
            'plan_currency' => 'required|string|in:INR,USD,EUR',
            'features' => 'required|array|min:1',
            'features.*' => 'required|string|min:1',
            'visibility' => 'required|boolean',
            'user_limit' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $formattedFeatures = [];
            foreach ($data['features'] as $index => $value) {
                $formattedFeatures["feature" . ($index + 1)] = $value;
            }
            $data['features'] = json_encode($formattedFeatures);

            $subscriptionPlan->update($data);

            DB::commit();

            ToastMagic::success('Plan updated successfully!');
            return redirect()->route('subscription-plans.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->delete();

        ToastMagic::success('Plan deleted successfully!');
        return redirect()->route('subscription-plans.index');
    }
}
