<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        return Inertia::render('subscriptionplan/Index');
    }

    public function create()
    {
        return Inertia::render('subscriptionplan/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plan_title' => 'required|string',
            'plan_description' => 'nullable|string',
            'plan_price' => 'required|numeric',
            'plan_currency' => 'string|in:INR,USD,EUR',
            'features' => 'nullable|array',
            'visibility' => 'boolean',
            'user_limit' => 'integer',
        ]);

        $plan = SubscriptionPlan::create($data);

        return response()->json($plan, 201);
    }

    public function show(SubscriptionPlan $subscriptionPlan)
    {
        return response()->json($subscriptionPlan);
    }

     public function edit(SubscriptionPlan $subscriptionplan)
    {
        // dd($organization);
        return Inertia::render('subscriptionplan/Edit', compact('organization'));
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $data = $request->validate([
            'plan_title' => 'sometimes|required|string',
            'plan_description' => 'nullable|string',
            'plan_price' => 'numeric',
            'plan_currency' => 'string|in:INR,USD,EUR',
            'features' => 'nullable|array',
            'visibility' => 'boolean',
            'user_limit' => 'integer',
        ]);

        $subscriptionPlan->update($data);

        return response()->json($subscriptionPlan);
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
