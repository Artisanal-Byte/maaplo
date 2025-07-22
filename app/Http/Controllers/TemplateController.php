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
use Illuminate\Support\Str;

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

        $groupedDesignDetails = DesignDetail::with('bodyPartValue')
            ->get(['id', 'body_part_id', 'value', 'gender', 'body_section'])
            ->groupBy('body_part_id')
            ->map(function ($group) {

                return [
                    'body_part' => optional($group->first()->bodyPartValue)->body_part,
                    'design_detail_ids' => $group->pluck('id'),
                    'gender' => $group->pluck('gender'),
                    'body_section' => $group->pluck('body_section'),
                ];
            })
            ->values();
        return Inertia::render('items/Create', [
            'publicTemplates' => $data['publicTemplates'],
            'privateTemplates' => $data['privateTemplates'],
            'measurements' => $data['allMeasurements'],
            'designDetailsGrouped' => $groupedDesignDetails,
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
            'svg_logo' => 'nullable|string|regex:/<svg.*<\/svg>/',
            'required_measurements' => 'required|array',
            'design_details' => 'required|array',
        ]);
        $groupedDesignDetails = DesignDetail::with('bodyPartValue')
            ->get(['id', 'body_part_id', 'value', 'gender', 'body_section'])
            ->groupBy('body_part_id');
        $selectedGroupedDetails = collect($validated['design_details'])
            ->filter(fn($val) => $val === true)
            ->keys()
            ->mapWithKeys(function ($index) use ($groupedDesignDetails, $validated) {
                $group = $groupedDesignDetails->values()[$index] ?? collect();

                $filteredGroup = $group->filter(function ($detail) use ($validated) {
                    return $detail->gender === $validated['gender']
                        && strtolower($detail->body_section) === strtolower($validated['body_part']);
                });

                $bodyPartLabelRaw = optional($filteredGroup->first()?->bodyPartValue)->body_part ?? 'bodypartvalue';
                $bodyPartLabel = Str::snake($bodyPartLabelRaw); // e.g., "front_neck"
                $ids = $filteredGroup->pluck('id')->toArray();

                return [$bodyPartLabel => $ids];
            })
            ->toArray();

        try {
            DB::beginTransaction();
            $template = Template::create([
                'user_id' => Auth::user()->id,
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'body_part' => $validated['body_part'],
                'svg_logo' => $validated['svg_logo'],
                'design_details' => json_encode($selectedGroupedDetails, JSON_UNESCAPED_SLASHES),

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
        // dd($item->toArray());
        $measurements = [
            'all' => Measurement::all(['id', 'slug', 'measurements_logo', 'body_part']),
            'selected' => $item->measurements()->pluck('slug')->toArray(),
        ];
        // Fetch template data using the helper
        $templateData = GetTemplateHelper::getTemplateData(auth()->id());
        return Inertia::render('items/Edit', [
            'item' => $item,
            'measurements' => $measurements,
            'publicTemplates' => $templateData['publicTemplates'],
            'privateTemplates' => $templateData['privateTemplates'],
            'allMeasurements' => $templateData['allMeasurements'],
            'designDetails' => DesignDetail::with('bodyPartValue')
                ->get(['id', 'body_part_id', 'value', 'gender', 'body_section'])
                ->groupBy('body_part_id')
                ->map(function ($group) {
                    return [
                        'body_part' => optional($group->first()->bodyPartValue)->body_part,
                        'design_detail_ids' => $group->pluck('id'),
                        'gender' => $group->pluck('gender'),
                        'body_section' => $group->pluck('body_section'),
                    ];
                })
                ->values(),
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:m,f,o',
            'body_part' => 'required|in:upper,lower',
            'svg_logo' => 'nullable|string',
            'required_measurements' => 'required|array',
            'design_details' => 'required|array',
        ]);

        $groupedDesignDetails = DesignDetail::with('bodyPartValue')
            ->get(['id', 'body_part_id', 'value', 'gender', 'body_section'])
            ->groupBy('body_part_id');

        $selectedGroupedDetails = collect($validated['design_details'])
            ->filter(fn($val) => $val === true)
            ->keys()
            ->mapWithKeys(function ($index) use ($groupedDesignDetails, $validated) {
                $group = $groupedDesignDetails->values()[$index] ?? collect();

                $filteredGroup = $group->filter(function ($detail) use ($validated) {
                    return $detail->gender === $validated['gender']
                        && strtolower($detail->body_section) === strtolower($validated['body_part']);
                });

                $bodyPartLabelRaw = optional($filteredGroup->first()?->bodyPartValue)->body_part ?? 'bodypartvalue';
                $bodyPartLabel = Str::snake($bodyPartLabelRaw);
                $ids = $filteredGroup->pluck('id')->toArray();

                return [$bodyPartLabel => $ids];
            })
            ->toArray();

        try {
            DB::beginTransaction();

            $template = Template::findOrFail($id);
            $template->update([
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'body_part' => $validated['body_part'],
                'svg_logo' => $validated['svg_logo'],
                'design_details' => json_encode($selectedGroupedDetails, JSON_UNESCAPED_SLASHES),
            ]);

            $measurementIds = Measurement::whereIn('slug', $validated['required_measurements'])->pluck('id')->toArray();
            $template->measurements()->sync($measurementIds);

            DB::commit();

            return redirect()->route('items.index')->with('success', 'Item updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
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
