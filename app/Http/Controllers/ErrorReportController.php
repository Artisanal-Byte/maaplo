<?php

namespace App\Http\Controllers;

use App\Models\ErrorReport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use App\Helpers\ImageHelper;

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
            'other_error_type' => 'required_if:error_type,Others|string|nullable',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('screenshot')) {
                $image = $request->file('screenshot');
                $user = auth()->user();

                $username = 'guest';
                if ($user) {
                    $username = $user->username
                        ?? $user->name
                        ?? (isset($user->email) ? explode('@', $user->email)[0] : 'guest');
                }

                $userId = auth()->id() ?? 0;

                $data['screenshot_path'] = \App\Helpers\ImageHelper::storeUserScreenshot($image, $username, $userId);
            }

            ErrorReport::create($data);

            DB::commit();

            ToastMagic::success('Your Report or Error send successfully! we will review it soon.');
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Creation failed: ' . $e->getMessage());
        }
    }


    public function index()
    {
        $reports = ErrorReport::latest()->get();
        return Inertia::render('reporterror/Index', ['reports' => $reports]);
    }
}
