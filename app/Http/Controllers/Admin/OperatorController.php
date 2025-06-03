<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\Motorcycle;
use App\Models\EmergencyContact;
use App\Models\Toda;
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
                'emergency_contact_no' => 'required|string'
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
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error creating operator: ' . $e->getMessage());
                return back()->withInput()
                    ->withErrors(['operator_error' => 'Failed to create operator record. Please check your input and try again.']);
            }

            try {
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
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error creating motorcycle: ' . $e->getMessage());
                return back()->withInput()
                    ->withErrors(['motorcycle_error' => 'Failed to create motorcycle record. The MTOP number or plate number might already be in use.']);
            }

            try {
                // Create emergency contact
                EmergencyContact::create([
                    'operator_id' => $operator->id,
                    'contact_person' => $validated['emergency_contact'],
                    'tel_no' => $validated['emergency_contact_no']
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error creating emergency contact: ' . $e->getMessage());
                return back()->withInput()
                    ->withErrors(['emergency_contact_error' => 'Failed to create emergency contact record.']);
            }

            try {
                DB::commit();
                return redirect()->route('operators.index')
                    ->with('success', 'Operator created successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error during transaction commit: ' . $e->getMessage());
                return back()->withInput()
                    ->withErrors(['database_error' => 'A database error occurred. Please try again.']);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
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
            'emergency_contact_no' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

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

            DB::commit();

            return redirect()->route('operators.index')
                ->with('success', 'Operator created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->withErrors(['error' => 'An error occurred while creating the operator. ' . $e->getMessage()]);
        }
    }
}
}