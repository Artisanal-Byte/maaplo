<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\BodyPartValue;
use App\Models\DesignDetail;
use App\Rules\SvgMarkup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DesignDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designDetails = DesignDetail::orderBy('created_at', 'desc')->get();

        return Inertia::render('designdetail/Index', [
            'designDetails' => $designDetails,
        ]);
    }

    public function create()
    {
        $bodyParts = BodyPartValue::select('id', 'body_part')->orderBy('body_part')->get();
        // dd($bodyParts);
        // Pass $bodyParts to the Inertia view
        return Inertia::render('designdetail/Create', [
            'bodyParts' => $bodyParts,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body_section' => 'required|in:Upper,Lower',
            'gender' => 'required|in:m,f,o',
            'body_part_id' => 'nullable|exists:body_part_value,id',
            'new_body_part' => 'nullable|string|max:255',
            'value' => 'required|string',
            'image' => ['required', 'string', new SvgMarkup()],
        ]);

        // Logic: if user selected existing body_part_id, use it
        if ($validated['body_part_id']) {
            $bodyPartId = $validated['body_part_id'];
        }
        // Otherwise, new_body_part should be provided
        elseif (!empty($validated['new_body_part'])) {
            $newBodyPart = trim($validated['new_body_part']);

            // Check if this new body part already exists
            $exists = BodyPartValue::where('body_part', $newBodyPart)->first();
            if ($exists) {
                return back()->withErrors(['body_part' => 'This Body Part already exists. Please select it from the dropdown.'])->withInput();
            }

            // Create new body part
            $bodyPart = BodyPartValue::create(['body_part' => $newBodyPart]);
            $bodyPartId = $bodyPart->id;
        } else {
            return back()->withErrors(['body_part' => 'Please select or enter a Body Part.'])->withInput();
        }

        // Now create DesignDetail with the body_part_id
        DesignDetail::create([
            'body_section' => $validated['body_section'],
            'gender' => $validated['gender'],
            'body_part' => $bodyPartId, // store id here
            'value' => $validated['value'],
            'image' => $validated['image'],
        ]);

        return redirect()->route('design-details.index');
    }


    public function edit(DesignDetail $designDetail)
    {
        $bodyParts = BodyPartValue::select('id', 'body_part')->orderBy('body_part')->get();
        return Inertia::render('designdetail/Edit', [
            'designDetail' => $designDetail,
            'bodyParts' => $bodyParts,
        ]);
    }

    public function update(Request $request, DesignDetail $designDetail)
    {
        $validated = $request->validate([
            'body_section' => 'required|in:Upper,Lower',
            'gender' => 'required|in:m,f,o',
            'body_part_id' => 'nullable|exists:body_part_value,id',
            'new_body_part' => 'nullable|string|max:255',
            'value' => 'required|string',
            'image' => ['required', 'string', new SvgMarkup()],
        ]);

        // Determine body part ID
        if (!empty($validated['body_part_id'])) {
            $bodyPartId = $validated['body_part_id'];
        } elseif (!empty($validated['new_body_part'])) {
            $newBodyPart = trim($validated['new_body_part']);

            // Check for duplicates
            $existing = BodyPartValue::where('body_part', $newBodyPart)->first();
            if ($existing) {
                return back()->withErrors(['body_part' => 'This Body Part already exists. Please select it from the dropdown.'])->withInput();
            }

            // Create new body part
            $bodyPart = BodyPartValue::create(['body_part' => $newBodyPart]);
            $bodyPartId = $bodyPart->id;
        } else {
            return back()->withErrors(['body_part' => 'Please select or enter a Body Part.'])->withInput();
        }

        // Update design detail
        $designDetail->update([
            'body_section' => $validated['body_section'],
            'gender' => $validated['gender'],
            'body_part' => $bodyPartId,
            'value' => $validated['value'],
            'image' => $validated['image'],
        ]);

        return redirect()->route('design-details.index');
    }


    public function destroy(DesignDetail $designDetail)
    {
        $designDetail->delete();

        return redirect()->route('design-details.index');
    }
}
