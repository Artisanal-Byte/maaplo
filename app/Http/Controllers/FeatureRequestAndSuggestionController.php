<?php

namespace App\Http\Controllers;

use App\Models\FeatureRequestAndSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeatureRequestAndSuggestionController extends Controller
{
    public function storeFeatureRequest(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'feature_name' => 'nullable|string|max:255',
            'feature_description' => 'nullable|string',
        ]);
// dd($request->all());
        FeatureRequestAndSuggestion::create([
            'user_id' => Auth::id(),
            'feature_name' => $request->feature_name,
            'feature_description' => $request->feature_description,
        ]);

        return redirect()->back()->with('success', 'Feature request submitted!');
    }

    public function storeSuggestion(Request $request)
    {
        $request->validate([
            'suggestion_title' => 'nullable|string|max:255',
            'suggestion_description' => 'nullable|string',
        ]);

        FeatureRequestAndSuggestion::create([
            'user_id' => Auth::id(),
            'suggestion_title' => $request->suggestion_title,
            'suggestion_description' => $request->suggestion_description,
        ]);

        return redirect()->back()->with('success', 'Suggestion submitted!');
    }
}

