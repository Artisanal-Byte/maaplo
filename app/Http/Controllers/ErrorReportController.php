<?php

namespace App\Http\Controllers;

use App\Models\ErrorReport;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ErrorReportController extends Controller
{
    public function create()
    {
        return Inertia::render('reporterror/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'screenshot' => 'nullable|image|max:2048',
            'url' => 'nullable|url',
            'error_type' => 'required|string',
            'other_error_type' => 'nullable|string',
        ]);

        if ($request->hasFile('screenshot')) {
            $data['screenshot_path'] = $request->file('screenshot')->store('screenshots', 'public');
        }

        ErrorReport::create($data);

        return redirect()->back()->with('success', 'Error reported successfully!');
    }

    public function index()
    {
        $reports = ErrorReport::latest()->get();
        return Inertia::render('reporterror/Index', ['reports' => $reports]);
    }
}
