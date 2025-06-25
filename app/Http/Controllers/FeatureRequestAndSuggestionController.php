<?php

namespace App\Http\Controllers;

use App\Models\FeatureRequestAndSuggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FeatureRequestAndSuggestionController extends Controller
{
    public function storeFeatureRequest(Request $request)
    {
        $request->validate([
            'feature_name' => 'nullable|string|max:255',
            'feature_description' => 'nullable|string',
        ]);

        try {
            FeatureRequestAndSuggestion::create([
                'user_id' => Auth::id(),
                'feature_name' => $request->feature_name,
                'feature_description' => $request->feature_description,
            ]);

            return redirect()->back()->with('success', 'Feature request submitted!');
        } catch (\Exception $e) {
            Log::error('Feature request failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while submitting your feature request. Please try again.');
        }
    }

    public function storeSuggestion(Request $request)
    {
        $request->validate([
            'suggestion_title' => 'nullable|string|max:255',
            'suggestion_description' => 'nullable|string',
        ]);

        try {
            FeatureRequestAndSuggestion::create([
                'user_id' => Auth::id(),
                'suggestion_title' => $request->suggestion_title,
                'suggestion_description' => $request->suggestion_description,
            ]);

            return redirect()->back()->with('success', 'Suggestion submitted!');
        } catch (\Exception $e) {
            Log::error('Suggestion submission failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while submitting your suggestion. Please try again.');
        }
    }
}
