<?php

namespace App\Http\Controllers;

use App\Models\FeatureRequestAndSuggestion;
use Illuminate\Http\Request;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeatureRequestAndSuggestionController extends Controller
{
    public function indexSuggestion()
    {
        $suggestions = FeatureRequestAndSuggestion::with('user')
            ->whereNotNull('suggestion_title')
            ->latest()
            ->paginate(10);
        $suggestions->getCollection()->transform(function ($suggestion) {
            return [
                'id' => $suggestion->id,
                'suggestion_title' => $suggestion->suggestion_title,
                'suggestion_description' => $suggestion->suggestion_description,
                'user' => [
                    'id' => $suggestion->user->id,
                    'name' => $suggestion->user->name,
                    'phone' => $suggestion->user->phone,
                ],
                'created_at' => $suggestion->created_at,
            ];
        });

        return inertia('suggestion/Index', [
            'suggestions' => $suggestions,
        ]);
    }


    public function indexFeatureRequest()
    {
        $featureRequests = FeatureRequestAndSuggestion::with('user')
            ->whereNotNull('feature_name')
            ->latest()
            ->paginate(1)
            ->through(function ($feature) {
                return [
                    'id' => $feature->id,
                    'feature_name' => $feature->feature_name,
                    'feature_description' => $feature->feature_description,
                    'feature_experience' => $feature->feature_experience,
                    'user' => [
                        'id' => $feature->user->id,
                        'name' => $feature->user->name,
                        'phone' => $feature->user->phone,
                    ],
                    'created_at' => $feature->created_at,
                ];
            });

        return inertia('featurerequest/Index', [
            'featureRequests' => $featureRequests,
        ]);
    }

    public function storeFeatureRequest(Request $request)
    {
        $request->validate([
            'feature_name' => 'required|string|max:255',
            'feature_description' => 'required|string',
            'feature_experience' => 'required|string',
        ]);
        // dd($request->all());
        try {
            FeatureRequestAndSuggestion::create([
                'user_id' => Auth::id(),
                'feature_name' => $request->feature_name,
                'feature_description' => $request->feature_description,
                'feature_experience' => $request->feature_experience,
            ]);
            // DB::commit();
            ToastMagic::success('Your Feature suggestion send successfully!');
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            Log::error('Feature request failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while submitting your feature request. Please try again.');
        }
    }

    public function storeSuggestion(Request $request)
    {
        $request->validate([
            'suggestion_title' => 'required|string|max:255',
            'suggestion_description' => 'required|string',
        ]);

        try {
            FeatureRequestAndSuggestion::create([
                'user_id' => Auth::id(),
                'suggestion_title' => $request->suggestion_title,
                'suggestion_description' => $request->suggestion_description,
            ]);
            DB::commit();
            ToastMagic::success('Your Suggestion send successfully! we will review it soon.');
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            Log::error('Suggestion submission failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong while submitting your suggestion. Please try again.');
        }
    }
}
