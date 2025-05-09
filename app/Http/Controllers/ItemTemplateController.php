<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ItemTemplateController extends Controller
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
        // dd($publicTemplates);
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

        // dd($validated);
        try {
            DB::beginTransaction();

            Template::create([
                'user_id' => Auth::user()->id,
                //  'global_scope' => $validated['global_scope'] ?? 'n',
                //  'custom_template' => $validated['custom_template'] ?? false,
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'body_part' => $validated['body_part'],
                'svg_logo' => $validated['svg_logo'],
                'required_measurements' => json_encode($validated['required_measurements']),
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = Template::findOrFail($id);

        return Inertia::render('items/Edit', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
