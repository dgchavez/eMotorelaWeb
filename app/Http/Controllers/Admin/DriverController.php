<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::query()
            ->with(['operator' => function($query) {
                $query->with('toda');
            }]);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('drivers_license_no', 'like', "%{$search}%");
            });
        }

        // Filter by operator
        if ($request->has('operator_id') && $request->operator_id != '') {
            $query->where('operator_id', $request->operator_id);
        }

        // Filter by license status
        if ($request->has('license_status') && $request->license_status != '') {
            $today = Carbon::today();
            if ($request->license_status === 'expired') {
                $query->where('license_expiry_date', '<', $today);
            } else {
                $query->where('license_expiry_date', '>=', $today);
            }
        }

        $drivers = $query->latest()->paginate(10);
        $operators = Operator::orderBy('last_name')->get();

        return view('admin.driversIndex', compact('drivers', 'operators'));
    }

    public function edit(Driver $driver)
    {
        $operators = Operator::orderBy('last_name')->get();
        return view('admin.driversEdit', compact('driver', 'operators'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'operator_id' => 'required|exists:operators,id',
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'address' => 'required|string',
            'contact_no' => 'required|string|max:20',
            'drivers_license_no' => 'required|string|max:50',
            'license_expiry_date' => 'required|date|after:today',
        ]);

        try {
            $driver->update($validated);
            return redirect()->route('admin.drivers.index')
                ->with('success', 'Driver updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'An error occurred while updating the driver.']);
        }
    }

    public function destroy(Driver $driver)
    {
        try {
            $driver->delete();
            return redirect()->route('admin.drivers.index')
                ->with('success', 'Driver removed successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while removing the driver.']);
        }
    }
}
