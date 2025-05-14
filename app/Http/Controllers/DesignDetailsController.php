<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\DesignDetail;
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
        if (Auth::user()->role != 'Super Admin' && !Auth::user()) {
            abort(404);
        }
        $designDetails = DesignDetail::all();
        return Inertia::render('designDetails/Index', ['designDetails' => $designDetails]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('designDetails/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'body_selection' => 'required|string|max:255',
            'body_part' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {

            DB::beginTransaction();

            DesignDetail::create($validated);

            DB::commit();
       
        } catch (\Exception $exception) {

            DB::rollBack();

            dd($exception->getMessage());
       
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
    public function edit(string $id)
    {
        //
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
