<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationTrackingController extends Controller
{
    public function track($trackingCode)
    {
        $application = Application::where('tracking_code', $trackingCode)
            ->with(['operator.toda'])
            ->firstOrFail();

        return view('application.track', [
            'application' => $application,
            'statusHistory' => $application->getStatusHistoryFormatted()
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'tracking_code' => 'required|string'
        ]);

        return redirect()->route('application.track', $request->tracking_code);
    }
} 