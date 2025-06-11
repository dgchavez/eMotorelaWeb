<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\Motorcycle;
use App\Models\EmergencyContact;
use App\Models\Toda;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperatorController extends Controller
{
    public function index(Request $request)
    {
        $query = Operator::query()->with(['toda', 'motorcycles']);

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhereHas('motorcycles', function($q) use ($search) {
                        $q->where('mtop_no', 'like', "%{$search}%")
                            ->orWhere('plate_no', 'like', "%{$search}%");
                    });
            });
        }

        // TODA filter
        if ($request->has('toda_id') && $request->toda_id !== '') {
            $query->where('toda_id', $request->toda_id);
        }

        $operators = $query->latest()->paginate(10);
        $todas = Toda::where('status', 'active')->get();

        return view('admin.operatorsIndex', compact('operators', 'todas'));
    }

    public function create()
    {
        $todas = Toda::where('status', 'active')->orderBy('name')->get();
        $drivers = Driver::orderBy('last_name')->get();
        return view('admin.operatorsCreate', compact('todas', 'drivers'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                // Operator details
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'address' => 'required|string',
                'contact_number' => 'required|string',
                'email' => 'nullable|email|max:255',
                'toda_id' => 'required|exists:todas,id',
                
                // Motorcycle details
                'mtop_no' => 'required|string|unique:motorcycles,mtop_no',
                'motor_no' => 'required|string',
                'chassis_no' => 'required|string',
                'make' => 'required|string',
                'year_model' => 'required|string',
                'mv_file_no' => 'required|string',
                'plate_no' => 'required|string|unique:motorcycles,plate_no',
                'color' => 'required|string',
                'registration_date' => 'required|date',

                // Emergency contact
                'emergency_contact' => 'required|string|max:255',
                'emergency_contact_no' => 'required|string',

                // Operator as driver validation
                'operator_is_driver' => 'required|boolean',
                'operator_license_no' => 'required_if:operator_is_driver,1|string|max:50',
                'operator_license_expiry' => 'required_if:operator_is_driver,1|date|after:today',

                // Drivers validation
                'drivers' => 'required|array|min:1',
                'drivers.*.last_name' => 'required|string|max:100',
                'drivers.*.first_name' => 'required|string|max:100',
                'drivers.*.middle_name' => 'nullable|string|max:100',
                'drivers.*.address' => 'required|string',
                'drivers.*.contact_no' => 'required|string|max:20',
                'drivers.*.drivers_license_no' => 'required|string|max:50',
                'drivers.*.license_expiry_date' => 'required|date|after:today'
            ]);

            DB::beginTransaction();

            try {
                // Create the operator
                $operator = Operator::create([
                    'first_name' => $validated['first_name'],
                    'middle_name' => $validated['middle_name'],
                    'last_name' => $validated['last_name'],
                    'address' => $validated['address'],
                    'contact_no' => $validated['contact_number'],
                    'email' => $validated['email'],
                    'toda_id' => $validated['toda_id']
                ]);

                // Create the motorcycle
                $motorcycle = Motorcycle::create([
                    'operator_id' => $operator->id,
                    'mtop_no' => $validated['mtop_no'],
                    'motor_no' => $validated['motor_no'],
                    'chassis_no' => $validated['chassis_no'],
                    'make' => $validated['make'],
                    'year_model' => $validated['year_model'],
                    'mv_file_no' => $validated['mv_file_no'],
                    'plate_no' => $validated['plate_no'],
                    'color' => $validated['color'],
                    'registration_date' => $validated['registration_date']
                ]);

                // Create emergency contact
                EmergencyContact::create([
                    'operator_id' => $operator->id,
                    'contact_person' => $validated['emergency_contact'],
                    'tel_no' => $validated['emergency_contact_no']
                ]);

                // Handle drivers
                $driverIds = [];
                
                // Handle operator as driver
                if ($validated['operator_is_driver']) {
                    $operatorDriver = Driver::updateOrCreate(
                        ['drivers_license_no' => $validated['operator_license_no']],
                        [
                            'last_name' => $validated['last_name'],
                            'first_name' => $validated['first_name'],
                            'middle_name' => $validated['middle_name'],
                            'address' => $validated['address'],
                            'contact_no' => $validated['contact_number'],
                            'license_expiry_date' => $validated['operator_license_expiry']
                        ]
                    );
                    $driverIds[] = $operatorDriver->id;
                }
                
                // Handle other drivers
                foreach ($validated['drivers'] as $driverData) {
                    // Skip if this is the operator-driver (to avoid duplicates)
                    if ($validated['operator_is_driver'] && 
                        isset($operatorDriver) && 
                        $driverData['drivers_license_no'] === $validated['operator_license_no']) {
                        continue;
                    }
                    
                    // Create or update the driver
                    $driver = Driver::updateOrCreate(
                        ['drivers_license_no' => $driverData['drivers_license_no']],
                        [
                            'last_name' => $driverData['last_name'],
                            'first_name' => $driverData['first_name'],
                            'middle_name' => $driverData['middle_name'] ?? null,
                            'address' => $driverData['address'],
                            'contact_no' => $driverData['contact_no'],
                            'license_expiry_date' => $driverData['license_expiry_date']
                        ]
                    );
                    
                    $driverIds[] = $driver->id;
                }

                // Attach all drivers to the operator using the pivot table
                $operator->drivers()->attach($driverIds);

                DB::commit();
                return redirect()->route('operators.index')
                    ->with('success', 'Operator and drivers created successfully.');

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error during operator creation: ' . $e->getMessage());
                return back()->withInput()
                    ->withErrors(['error' => 'An error occurred while creating the operator and drivers. ' . $e->getMessage()]);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        }
    }

    public function show(Operator $operator)
    {
        return response()->json([
            'success' => true,
            'html' => view('admin.operators.show-modal', compact('operator'))->render()
        ]);
    }

    public function edit(Operator $operator)
    {
        // Get the operator's first motorcycle with explicit loading
        $motorcycle = $operator->motorcycles()->first();
        
        // If no motorcycle exists, create an empty object to avoid null errors
        if (!$motorcycle) {
            $motorcycle = new \stdClass();
            $motorcycle->registration_date = null;
            $motorcycle->mtop_no = '';
            $motorcycle->motor_no = '';
            $motorcycle->chassis_no = '';
            $motorcycle->make = '';
            $motorcycle->year_model = '';
            $motorcycle->mv_file_no = '';
            $motorcycle->plate_no = '';
            $motorcycle->color = '';
        } else {
            // Ensure the date is properly formatted
            $motorcycle->registration_date = $motorcycle->registration_date ? date('Y-m-d', strtotime($motorcycle->registration_date)) : null;
        }
        
        $emergencyContact = $operator->emergencyContact;
        $todas = Toda::where('status', 'active')->orderBy('name')->get();
        $drivers = Driver::orderBy('last_name')->get();
        
        // Check if operator is also a driver - check both current relationship and existing driver record
        $operatorDriver = Driver::where(function($query) use ($operator) {
                $query->whereHas('operators', function($q) use ($operator) {
                    $q->where('operators.id', $operator->id);
                })
                ->orWhere(function($q) use ($operator) {
                    $q->where('drivers_license_no', '!=', '')
                      ->where('last_name', $operator->last_name)
                      ->where('first_name', $operator->first_name)
                      ->where('address', $operator->address)
                      ->where('contact_no', $operator->contact_no);
                });
            })->first();
        
        $driversArray = $operator->drivers->map(function($driver) use ($operator, $operatorDriver) {
            return [
                'id' => $driver->id,
                'last_name' => $driver->last_name,
                'first_name' => $driver->first_name,
                'middle_name' => $driver->middle_name,
                'address' => $driver->address,
                'contact_no' => $driver->contact_no,
                'drivers_license_no' => $driver->drivers_license_no,
                'license_expiry_date' => $driver->license_expiry_date ? $driver->license_expiry_date->format('Y-m-d') : null,
                '_isOperator' => $operatorDriver && $driver->id === $operatorDriver->id
            ];
        })->toArray();

        return view('admin.operators.edit', compact(
            'operator',
            'motorcycle',
            'emergencyContact',
            'todas',
            'drivers',
            'driversArray',
            'operatorDriver'
        ));
    }

    public function update(Request $request, Operator $operator)
    {
        $motorcycle = $operator->motorcycles->first();
        
        $validated = $request->validate([
            // Operator details
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'toda_id' => 'required|exists:todas,id',
            
            // Motorcycle details
            'mtop_no' => 'required|string|unique:motorcycles,mtop_no,' . ($motorcycle ? $motorcycle->id : ''),
            'motor_no' => 'required|string',
            'chassis_no' => 'required|string',
            'make' => 'required|string',
            'year_model' => 'required|string',
            'mv_file_no' => 'required|string',
            'plate_no' => 'required|string|unique:motorcycles,plate_no,' . ($motorcycle ? $motorcycle->id : ''),
            'color' => 'required|string',
            'registration_date' => 'required|date',

            // Emergency contact
            'emergency_contact' => 'required|string|max:255',
            'emergency_contact_no' => 'required|string',

            // Operator as driver validation
            'operator_is_driver' => 'required|boolean',
            'operator_license_no' => 'nullable|required_if:operator_is_driver,1|string|max:50',
            'operator_license_expiry' => 'nullable|required_if:operator_is_driver,1|date|after_or_equal:today',

            // Drivers validation
            'drivers' => 'nullable|array',
            'drivers.*.id' => 'sometimes|exists:drivers,id',
            'drivers.*.last_name' => 'required_with:drivers|string|max:100',
            'drivers.*.first_name' => 'required_with:drivers|string|max:100',
            'drivers.*.middle_name' => 'nullable|string|max:100',
            'drivers.*.address' => 'required_with:drivers|string',
            'drivers.*.contact_no' => 'required_with:drivers|string',
            'drivers.*.drivers_license_no' => 'required_with:drivers|string|max:50',
            'drivers.*.license_expiry_date' => 'required_with:drivers|date'
        ]);

        DB::beginTransaction();

        try {
            // Update operator details
            $operator->update([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'address' => $validated['address'],
                'contact_no' => $validated['contact_number'],
                'email' => $validated['email'],
                'toda_id' => $validated['toda_id']
            ]);

            // Update or create motorcycle
            if ($motorcycle) {
                $motorcycle->update([
                    'mtop_no' => $validated['mtop_no'],
                    'motor_no' => $validated['motor_no'],
                    'chassis_no' => $validated['chassis_no'],
                    'make' => $validated['make'],
                    'year_model' => $validated['year_model'],
                    'mv_file_no' => $validated['mv_file_no'],
                    'plate_no' => $validated['plate_no'],
                    'color' => $validated['color'],
                    'registration_date' => $validated['registration_date']
                ]);
            } else {
                $operator->motorcycles()->create([
                    'mtop_no' => $validated['mtop_no'],
                    'motor_no' => $validated['motor_no'],
                    'chassis_no' => $validated['chassis_no'],
                    'make' => $validated['make'],
                    'year_model' => $validated['year_model'],
                    'mv_file_no' => $validated['mv_file_no'],
                    'plate_no' => $validated['plate_no'],
                    'color' => $validated['color'],
                    'registration_date' => $validated['registration_date']
                ]);
            }

            // Update emergency contact
            $operator->emergencyContact()->updateOrCreate(
                ['operator_id' => $operator->id],
                [
                    'contact_person' => $validated['emergency_contact'],
                    'tel_no' => $validated['emergency_contact_no']
                ]
            );

            // Handle drivers
            $driverIds = [];
            
            // If operator is no longer a driver, remove the relationship
            if (!$validated['operator_is_driver']) {
                // Find and detach the operator-driver if it exists
                $operatorDriver = Driver::where(function($query) use ($operator) {
                    $query->whereHas('operators', function($q) use ($operator) {
                        $q->where('operators.id', $operator->id);
                    })
                    ->orWhere(function($q) use ($operator) {
                        $q->where('drivers_license_no', '!=', '')
                          ->where('last_name', $operator->last_name)
                          ->where('first_name', $operator->first_name)
                          ->where('address', $operator->address)
                          ->where('contact_no', $operator->contact_no);
                    });
                })->first();

                if ($operatorDriver) {
                    $operator->drivers()->detach($operatorDriver->id);
                }
            }
            // Handle operator as driver if checked
            else if ($validated['operator_is_driver']) {
                $operatorDriver = Driver::updateOrCreate(
                    ['drivers_license_no' => $validated['operator_license_no']],
                    [
                        'last_name' => $validated['last_name'],
                        'first_name' => $validated['first_name'],
                        'middle_name' => $validated['middle_name'],
                        'address' => $validated['address'],
                        'contact_no' => $validated['contact_number'],
                        'license_expiry_date' => $validated['operator_license_expiry']
                    ]
                );
                $driverIds[] = $operatorDriver->id;
            }
            
            // Handle other drivers
            if (isset($validated['drivers']) && is_array($validated['drivers'])) {
                foreach ($validated['drivers'] as $driverData) {
                    // Skip if this is the operator-driver (to avoid duplicates)
                    if ($validated['operator_is_driver'] && 
                        isset($operatorDriver) && 
                        isset($driverData['drivers_license_no']) &&
                        $driverData['drivers_license_no'] === $validated['operator_license_no']) {
                        continue;
                    }
                    
                    // Create or update the driver
                    $driver = Driver::updateOrCreate(
                        ['drivers_license_no' => $driverData['drivers_license_no']],
                        [
                            'last_name' => $driverData['last_name'],
                            'first_name' => $driverData['first_name'],
                            'middle_name' => $driverData['middle_name'] ?? null,
                            'address' => $driverData['address'],
                            'contact_no' => $driverData['contact_no'],
                            'license_expiry_date' => $driverData['license_expiry_date']
                        ]
                    );
                    
                    $driverIds[] = $driver->id;
                }
            }

            // Sync all remaining driver relationships
            $operator->drivers()->sync($driverIds);

            DB::commit();
            return redirect()->route('operators.index')
                ->with('success', 'Operator updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update error: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'An error occurred while updating the operator: ' . $e->getMessage()]);
        }
    }

    public function destroy(Operator $operator)
    {
        try {
            DB::beginTransaction();

            // Log the operator details before deletion
            \Log::info('Attempting to delete operator', [
                'operator_id' => $operator->id,
                'name' => $operator->full_name,
                'toda' => $operator->toda->name ?? null,
                'status' => $operator->status
            ]);

            // Delete the operator (this will cascade to related records)
            $operator->delete();

            DB::commit();
            \Log::info('Operator deleted successfully');

            return redirect()->route('operators.index')
                ->with('success', 'Operator deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting operator: ' . $e->getMessage(), [
                'operator_id' => $operator->id,
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'An error occurred while deleting the operator: ' . $e->getMessage());
        }
    }

    /**
     * Find a driver record matching the operator's details
     */
    public function findDriver(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'address' => 'required|string',
            'contact_no' => 'required|string'
        ]);

        $driver = Driver::where('drivers_license_no', '!=', '')
            ->where('last_name', $validated['last_name'])
            ->where('first_name', $validated['first_name'])
            ->where('address', $validated['address'])
            ->where('contact_no', $validated['contact_no'])
            ->first();

        return response()->json([
            'success' => true,
            'driver' => $driver ? [
                'id' => $driver->id,
                'last_name' => $driver->last_name,
                'first_name' => $driver->first_name,
                'middle_name' => $driver->middle_name,
                'address' => $driver->address,
                'contact_no' => $driver->contact_no,
                'drivers_license_no' => $driver->drivers_license_no,
                'license_expiry_date' => $driver->license_expiry_date ? $driver->license_expiry_date->format('Y-m-d') : null
            ] : null
        ]);
    }
}