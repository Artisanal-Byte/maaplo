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
use Devrabiul\ToastMagic\Facades\ToastMagic;

class DesignDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designDetails = DesignDetail::with('bodyPartValue') // Load related body part
            ->orderBy('created_at', 'desc')
            ->get();

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
        try {
            DB::beginTransaction();

            // Ensure one of the two fields is filled
            if (!$request->filled('body_part_id') && !$request->filled('new_body_part')) {
                return back()->withErrors([
                    'body_part_id' => 'Either select an existing Body Part or enter a new one.',
                    'new_body_part' => 'Either select an existing Body Part or enter a new one.',
                ])->withInput();
            }

            // Use existing body_part_id if provided
            if ($validated['body_part_id']) {
                $bodyPartId = $validated['body_part_id'];
            }
            // Otherwise, create a new body part
            else {
                $newBodyPart = trim($validated['new_body_part']);

                // Check if this new body part already exists
                $exists = BodyPartValue::where('body_part', $newBodyPart)->first();
                if ($exists) {
                    return back()->withErrors([
                        'new_body_part' => 'This Body Part already exists. Please select it from the dropdown.',
                    ])->withInput();
                }

                $bodyPart = BodyPartValue::create(['body_part' => $newBodyPart]);
                $bodyPartId = $bodyPart->id;
            }

            // Save DesignDetail with the proper body_part_id column
            DesignDetail::create([
                'body_section' => $validated['body_section'],
                'gender' => $validated['gender'],
                'body_part_id' => $bodyPartId, // make sure DB column is body_part_id!
                'value' => $validated['value'],
                'image' => $validated['image'],
            ]);
            DB::commit();
            ToastMagic::success('Design Detail Create successfully!');
            return redirect()->route('design-details.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Creation failed: ' . $e->getMessage());
        }
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
        try {
            DB::beginTransaction();

            // Determine body part ID
            if (!$request->filled('body_part_id') && !$request->filled('new_body_part')) {
                return back()->withErrors([
                    'body_part_id' => 'Either select an existing Body Part or enter a new one.',
                    'new_body_part' => 'Either select an existing Body Part or enter a new one.',
                ])->withInput();
            }

            if ($validated['body_part_id']) {
                $bodyPartId = $validated['body_part_id'];
                BodyPartValue::updateOrCreate(
                    ['id' => $bodyPartId],
                    ['body_part' => $validated['value']] // Update the body part name if needed
                );
            } else {
                $newBodyPart = trim($validated['new_body_part']);

                $exists = BodyPartValue::where('body_part', $newBodyPart)->first();
                if ($exists) {
                    return back()->withErrors([
                        'new_body_part' => 'This Body Part already exists. Please select it from the dropdown.',
                    ])->withInput();
                }

                $bodyPart = BodyPartValue::create(['body_part' => $newBodyPart]);
                $bodyPartId = $bodyPart->id;
            }


            // Update design detail
            $designDetail->update([
                'body_section' => $validated['body_section'],
                'body_part_id' => $bodyPartId, // make sure DB column is body_part_id!
                'gender' => $validated['gender'],
                'body_part' => $bodyPartId,
                'value' => $validated['value'],
                'image' => $validated['image'],
            ]);
            DB::commit();
            ToastMagic::success('Design Detail Updated successfully!');
            return redirect()->route('design-details.index');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }


    public function destroy(DesignDetail $designDetail)
    {
        $designDetail->delete();

        return redirect()->route('design-details.index');
    }
}
