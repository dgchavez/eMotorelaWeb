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
        $todas = Toda::where('status', 'active')->get();
        return view('admin.operatorsCreate', compact('todas'));
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

                // Create drivers
                foreach ($validated['drivers'] as $driverData) {
                    Driver::create([
                        'operator_id' => $operator->id,
                        'last_name' => $driverData['last_name'],
                        'first_name' => $driverData['first_name'],
                        'middle_name' => $driverData['middle_name'] ?? null,
                        'address' => $driverData['address'],
                        'contact_no' => $driverData['contact_no'],
                        'drivers_license_no' => $driverData['drivers_license_no'],
                        'license_expiry_date' => $driverData['license_expiry_date']
                    ]);
                }

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
        $todas = Toda::where('status', 'active')->orderBy('name')->get();
        return view('admin.operators.edit', compact('operator', 'todas'));
    }

    public function update(Request $request, Operator $operator)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_no' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:1000',
            'toda_id' => 'required|exists:todas,id',
            'status' => 'required|in:active,inactive',
            'mtop_no' => 'required|string|max:255',
            'plate_no' => 'required|string|max:255',
            'make' => 'required|string|max:255',
            'motor_no' => 'required|string|max:255',
            'chassis_no' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update operator details
            $operator->update([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'contact_no' => $validated['contact_no'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'toda_id' => $validated['toda_id'],
                'status' => $validated['status'],
            ]);

            // Update motorcycle details
            if ($motorcycle = $operator->motorcycles->first()) {
                $motorcycle->update([
                    'mtop_no' => $validated['mtop_no'],
                    'plate_no' => $validated['plate_no'],
                    'make' => $validated['make'],
                    'motor_no' => $validated['motor_no'],
                    'chassis_no' => $validated['chassis_no'],
                ]);
            }

            DB::commit();
            return redirect()->route('operators.index')
                ->with('success', 'Operator updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'An error occurred while updating the operator. Please try again.');
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
}