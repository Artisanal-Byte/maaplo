<?php

namespace App\Http\Controllers;

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
        $items = Template::latest()->get();
        $authUser = Auth::user();

        return Inertia::render('items/Index', [
            'items' => $items,
            'authUser' => $authUser,
            'publicTemplates' => $items,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $publicTemplates = Template::whereNull('user_id')->get();
        $allMeasurements = Measurement::select('id', 'slug')->get();
        return Inertia::render('items/Create', [
            'publicTemplates' => $publicTemplates,
            'measurements' => $allMeasurements,  // Pass the measurements with 'slug' and 'name'
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
            'svg_logo' => 'required|string',
            'required_measurements' => 'required|array',
        ]);
        // dd($validated);
        try {
            DB::beginTransaction();

            $template = Template::create([
                'user_id' => Auth::user()->id,
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'body_part' => $validated['body_part'],
                'svg_logo' => $validated['svg_logo'],
            ]);

            // Get the IDs of the selected measurements by slug
            $measurementIds = Measurement::whereIn('slug', $validated['required_measurements'])->pluck('id');
            // dd($measurementIds);
            // Insert into templates_measurements table
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
        $selectedMeasurementSlugs = $item->measurements()->pluck('slug')->toArray();
        $allMeasurements = Measurement::select('id', 'slug')->get();

        return Inertia::render('items/Edit', [
            'item' => $item,
            'measurements' => [
                'all' => $allMeasurements,
                'selected' => $selectedMeasurementSlugs,
            ],
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
            'svg_logo' => 'required|string',
            'required_measurements' => 'required|array',
        ]);
        try {
            DB::beginTransaction();

            $template = Template::findOrFail($id);
            $template->update([
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'body_part' => $validated['body_part'],
                'svg_logo' => $validated['svg_logo'],
            ]);
            // Get the IDs of the selected measurements by slug
            $measurementIds = Measurement::whereIn('slug', $validated['required_measurements'])->pluck('id')->toArray();

            // Sync pivot table (replaces old with new)
            $template->measurements()->sync($measurementIds);
            DB::commit();
            return redirect()->route('items.index')->with('success', 'Item updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Update failed: ' . $e->getMessage());
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
