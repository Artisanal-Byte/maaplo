<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
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
        return Inertia::render('designdetail/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body_section' => 'required|in:Upper,Lower',
            'gender' => 'required|in:m,f,o',
            'body_part' => 'required|string|max:255',
            'value' => 'required|string',
            'image' => ['required', 'string', new SvgMarkup()],
        ]);
        DesignDetail::create($validated);

        return redirect()->route('design-details.index');
    }

    public function edit(DesignDetail $designDetail)
    {
        return Inertia::render('designdetail/Edit', [
            'designDetail' => $designDetail,
        ]);
    }

    public function update(Request $request, DesignDetail $designDetail)
    {
        $validated = $request->validate([
            'body_section' => 'required|in:Upper,Lower',
            'gender' => 'required|in:m,f,o',
            'body_part' => 'required|string|max:255',
            'value' => 'required|string',
            'image' => ['required', 'string', new SvgMarkup()],
        ]);

        $designDetail->update($validated);

        return redirect()->route('design-details.index');
    }

    public function destroy(DesignDetail $designDetail)
    {
        $designDetail->delete();

        return redirect()->route('design-details.index');
    }
}
