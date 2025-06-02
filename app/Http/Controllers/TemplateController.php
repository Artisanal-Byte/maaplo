<?php

namespace App\Http\Controllers;

use App\Helpers\GetTemplateHelper;
use App\Models\DesignDetail;
use App\Models\Measurement;
use App\Models\Template;
use App\Models\TemplateMeasurement;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = Auth::user();
        $items = Template::where('user_id', $authUser->id)
            ->orWhereNull('user_id')
            ->latest()
            ->get();
        return Inertia::render('items/Index', [
            'items' => $items,
            'authUser' => $authUser,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = GetTemplateHelper::getTemplateData();
        return Inertia::render('items/Create', [
            'publicTemplates' => $data['publicTemplates'],
            'privateTemplates' => $data['privateTemplates'],
            'measurements' => $data['allMeasurements'],
            'designDetails' => DesignDetail::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:m,f,o',
            'body_part' => 'required|in:upper,lower',
            'svg_logo' => 'nullable|string|regex:/<svg.*<\/svg>/',
            'required_measurements' => 'required|array',
            'design_details' => 'required|array',
        ]);
        $trueDesignDetails = collect($validated['design_details'])
            ->filter(fn($val) => $val === true)
            ->keys()
            ->map(fn($key) => (int) $key)
            ->values()
            ->toArray();

        try {
            DB::beginTransaction();
            $template = Template::create([
                'user_id' => Auth::user()->id,
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'body_part' => $validated['body_part'],
                'svg_logo' => $validated['svg_logo'],
                'design_details' => $trueDesignDetails,
            ]);
            $measurementIds = Measurement::whereIn('slug', $validated['required_measurements'])->pluck('id');
            foreach ($measurementIds as $measurementId) {
                TemplateMeasurement::create([
                    'template_id' => $template->id,
                    'measurements_id' => $measurementId,
                ]);
            }

            DB::commit();
            return redirect()->route('items.index')->with('success', 'Item created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = Template::findOrFail($id);
        $measurements = [
            'all' => Measurement::all(['id', 'slug', 'measurements_logo']),
            'selected' => $item->measurements()->pluck('slug')->toArray(),
        ];
        // Fetch template data using the helper
        $templateData = GetTemplateHelper::getTemplateData(auth()->id());
        return Inertia::render('items/Edit', [
            'item' => $item,
            'errors' => [],
            'measurements' => $measurements,
            'publicTemplates' => $templateData['publicTemplates'],
            'privateTemplates' => $templateData['privateTemplates'],
            'allMeasurements' => $templateData['allMeasurements'],
            'designDetails' => DesignDetail::all(['id', 'value', 'body_part', 'gender']),
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:m,f',
            'body_part' => 'required|in:upper,lower',
            'svg_logo' => 'nullable|string|regex:/<svg.*<\/svg>/',
            'required_measurements' => 'required|array',
            'design_details' => 'required|array',
        ]);
        dd($validated);
        $trueDesignDetails = array_map('intval', $validated['design_details']);

        // dd($trueDesignDetails);
        try {
            DB::beginTransaction();

            $template = Template::findOrFail($id);
            $template->update([
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'body_part' => $validated['body_part'],
                'svg_logo' => $validated['svg_logo'],
                'design_details' => $trueDesignDetails,
            ]);
            // Get the IDs of the selected measurements by slug
            $measurementIds = Measurement::whereIn('slug', $validated['required_measurements'])->pluck('id')->toArray();

            // Sync pivot table (replaces old with new)
            $template->measurements()->sync($measurementIds);
            DB::commit();
            return redirect()->route('items.index')->with('success', 'Item updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($validated->errors())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $template = Template::find($id);
        if (!$template) {
            return redirect()->route('items.index')->with('error', 'Item not found!');
        }
        $template->delete();
        return redirect()->route('items.index')->with('status', 'Item soft deleted successfully!');
    }
}
