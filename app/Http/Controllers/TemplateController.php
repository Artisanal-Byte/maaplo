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
        $items = Template::all();
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
        return Inertia::render('items/Create', [
            'publicTemplates' => $publicTemplates,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:m,f,o',
            'body_part' => 'required|in:upper,lower',
            'svg_logo' => 'required|string',
            'required_measurements' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            Template::create([
                'user_id' => Auth::user()->id,
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'body_part' => $validated['body_part'],
                'svg_logo' => $validated['svg_logo'],
            ]);

            Measurement::create([
                'slug' => json_encode($validated['required_measurements']), // Store the measurements as JSON
            ]);

            // Get the IDs of the created records
            $template = Template::all()->last()->id;
            $measurement = Measurement::all()->last()->id;
            // dd( $template, $measurement);
            TemplateMeasurement::create([
                'template_id' => $template, // Store Template ID
                'measurements_id' => $measurement, // Store Measurement ID
            ]);

            DB::commit();

            // Redirect with flash message
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
        $measurements = Measurement::findOrFail($id);
        return Inertia::render('items/Edit', [
            'item' => $item,
            'measurements' => $measurements,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
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
            // Update related measurements
            $templateMeasurement = TemplateMeasurement::where('template_id', $template->id)->first();
            $measurement = Measurement::find($templateMeasurement->measurements_id);
            if ($measurement) {
                $measurement->update([
                    'slug' => json_encode($validated['required_measurements']), // Only encode once
                ]);
            }
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
