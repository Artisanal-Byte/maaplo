<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerLimit
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        $plan = $user->subscriptionPlan;
        $limit = ($plan && is_numeric($plan->user_limit)) ? (int) $plan->user_limit : 5;
        $customerCount = $user->customers()->count();

        if ($customerCount >= $limit) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Customer limit reached.',
                    'plan_title' => $plan?->plan_title ?? 'Free',
                    'plan_limit' => $limit,
                ], 403);
            }

            return redirect()
                ->route('customers.index')
                ->with('limit_reached', true);
        }

        return $next($request);
    }
}
