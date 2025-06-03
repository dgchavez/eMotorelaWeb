<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Operator;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::query()
            ->with(['operator' => function($query) {
                $query->with('toda');
            }]);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('drivers_license_no', 'like', "%{$search}%");
            });
        }

        // Filter by operator
        if ($request->has('operator_id')) {
            $query->where('operator_id', $request->operator_id);
        }

        $drivers = $query->latest()->paginate(10);
        $operators = Operator::all();

        return view('admin.driversIndex', compact('drivers', 'operators'));
    }
}
