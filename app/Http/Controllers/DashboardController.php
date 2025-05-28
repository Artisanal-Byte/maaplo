<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        $organizationName = optional($user->organization)->organization_name;
        $showOrganizationPopup = $user->hash_organization && is_null($organizationName);

        return Inertia::render('Dashboard', [
            'organizationName' => $organizationName,
            'showOrganizationPopup' => $showOrganizationPopup,
        ]);
    }
}
