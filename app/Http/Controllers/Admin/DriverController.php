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
            ->with(['operators' => function($query) {
                $query->with(['toda', 'motorcycles']);
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
            $query->whereHas('operators', function($q) use ($request) {
                $q->where('operators.id', $request->operator_id);
            });
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

    public function create()
    {
        $operators = Operator::with('motorcycles')->get();
        return view('admin.driversCreate', compact('operators'));
    }

    public function edit(Driver $driver)
    {
        $operators = Operator::with('motorcycles')->get();
        return view('admin.driversEdit', compact('driver', 'operators'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'address' => 'required|string',
            'contact_no' => 'required|string|max:255',
            'drivers_license_no' => 'required|string|max:255|unique:drivers,drivers_license_no,' . $driver->id,
            'license_expiry_date' => 'required|date',
            'operator_ids' => 'required|array',
            'operator_ids.*' => 'exists:operators,id'
        ]);

        try {
            DB::beginTransaction();

            $driver->update([
                'last_name' => $validated['last_name'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'suffix' => $validated['suffix'],
                'address' => $validated['address'],
                'contact_no' => $validated['contact_no'],
                'drivers_license_no' => $validated['drivers_license_no'],
                'license_expiry_date' => $validated['license_expiry_date'],
            ]);

            $driver->operators()->sync($validated['operator_ids']);

            DB::commit();

            return redirect()->route('admin.drivers.index')
                ->with('success', 'Driver updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update driver. Please try again.');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'address' => 'required|string',
            'contact_no' => 'required|string|max:255',
            'drivers_license_no' => 'required|string|max:255|unique:drivers',
            'license_expiry_date' => 'required|date',
            'operator_ids' => 'required|array',
            'operator_ids.*' => 'exists:operators,id'
        ]);

        try {
            DB::beginTransaction();

            $driver = Driver::create([
                'last_name' => $validated['last_name'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'suffix' => $validated['suffix'],
                'address' => $validated['address'],
                'contact_no' => $validated['contact_no'],
                'drivers_license_no' => $validated['drivers_license_no'],
                'license_expiry_date' => $validated['license_expiry_date'],
            ]);

            $driver->operators()->attach($validated['operator_ids']);

            DB::commit();

            return redirect()->route('admin.drivers.index')
                ->with('success', 'Driver created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create driver. Please try again.');
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

    public function getMotorcycles(Driver $driver)
    {
        try {
            // Get all operators associated with this driver with their motorcycles
            $operators = $driver->operators()
                ->with(['motorcycles', 'toda'])
                ->get();
            
            $units = $operators->flatMap(function($operator) {
                return $operator->motorcycles->map(function($motorcycle) use ($operator) {
                    return [
                        'operator' => [
                            'name' => $operator->last_name . ', ' . $operator->first_name,
                            'toda' => $operator->toda ? $operator->toda->name : 'N/A'
                        ],
                        'unit' => [
                            'plate_no' => $motorcycle->plate_no,
                            'mtop_no' => $motorcycle->mtop_no,
                            'make' => $motorcycle->make,
                            'year_model' => $motorcycle->year_model,
                            'color' => $motorcycle->color,
                            'motor_no' => $motorcycle->motor_no,
                            'chassis_no' => $motorcycle->chassis_no,
                            'mv_file_no' => $motorcycle->mv_file_no,
                            'registration_date' => $motorcycle->registration_date->format('M d, Y')
                        ]
                    ];
                });
            })->values();

            return response()->json([
                'success' => true,
                'data' => [
                    'driver' => [
                        'name' => $driver->full_name,
                        'license_no' => $driver->drivers_license_no
                    ],
                    'units' => $units
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching motorcycles: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch unit data: ' . $e->getMessage()
            ], 500);
        }
    }
}
