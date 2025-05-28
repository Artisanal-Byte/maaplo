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
        $user = $request->user();
        $organizationName = optional($user->organization)->organization_name;

        return Inertia::render('Dashboard', [
            'showOrganizationPopup' => $user->hash_organization !== false,
             'organizationName' => $organizationName,
        ]);
    }
}
