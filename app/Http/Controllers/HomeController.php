<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch all plans and sort by plan_price (as float)
        $subscriptionPlans = SubscriptionPlan::all()
            ->sortBy(function ($plan) {
                return floatval($plan->plan_price);
            })
            ->values(); // reindex the collection

        // Normalize 'features' to array
        foreach ($subscriptionPlans as $plan) {
            if (is_string($plan->features)) {
                $plan->features = json_decode($plan->features, true);
            }
        }

        return view('home', compact('subscriptionPlans'));
    }
}
