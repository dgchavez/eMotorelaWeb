<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toda;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class TodaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Toda::query()
            ->withCount('operators')
            ->when($request->filled('search'), function($query) use ($request) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('president', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function($query) use ($request) {
                $query->where('status', $request->status);
            });

        $todas = $query->latest()->paginate(10)->withQueryString();

        return view('admin.todaIndex', compact('todas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.todaCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'toda_name' => 'required|string|max:100|unique:todas,name',
            'toda_president' => 'required|string|max:100',
            'registration_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            Toda::create([
                'name' => $validated['toda_name'],
                'president' => $validated['toda_president'],
                'registration_date' => $validated['registration_date'],
                'description' => $validated['description'],
                'status' => $validated['status'],
            ]);

            return redirect()->route('toda.index')
                ->with('success', 'TODA created successfully.');
        } catch (\Exception $e) {
            \Log::error('TODA Creation Error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while creating the TODA: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $todaName)
    {
        $members = TodaMembership::where('toda_name', $todaName)
            ->with('operator')
            ->paginate(10);

        return view('admin.todaShow', compact('members', 'todaName'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Toda $toda)
    {
        return view('admin.todaEdit', compact('toda'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Toda $toda)
    {
        $validated = $request->validate([
            'toda_name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('todas', 'name')->ignore($toda->id)
            ],
            'toda_president' => 'required|string|max:100',
            'registration_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $toda->status;
            
            $toda->update([
                'name' => $validated['toda_name'],
                'president' => $validated['toda_president'],
                'registration_date' => $validated['registration_date'],
                'description' => $validated['description'],
                'status' => $validated['status'],
            ]);

            // If TODA is being marked as inactive
            if ($oldStatus === 'active' && $validated['status'] === 'inactive') {
                // Update all associated operators to inactive
                $toda->operators()->update(['status' => 'inactive']);
            }

            DB::commit();

            return redirect()->route('toda.index')
                ->with('success', 'TODA updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('TODA Update Error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while updating the TODA.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Toda $toda)
    {
        try {
            DB::beginTransaction();

            // Check if TODA has any operators
            $operatorsCount = $toda->operators()->count();
            
            if ($operatorsCount > 0) {
                return back()->withErrors([
                    'error' => "Cannot delete TODA. There are {$operatorsCount} operators associated with this TODA. Please reassign or remove the operators first."
                ]);
            }

            // Proceed with deletion
            $toda->delete();

            DB::commit();

            return redirect()->route('toda.index')
                ->with('success', 'TODA deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('TODA Deletion Error: ' . $e->getMessage());
            
            return back()->withErrors([
                'error' => 'An error occurred while deleting the TODA.'
            ]);
        }
    }

    public function toggleStatus(Request $request, Toda $toda)
    {
        try {
            DB::beginTransaction();

            $newStatus = $toda->status === 'active' ? 'inactive' : 'active';
            
            // Log the attempt
            Log::info('Attempting to toggle TODA status', [
                'toda_id' => $toda->id,
                'current_status' => $toda->status,
                'new_status' => $newStatus
            ]);

            // If being deactivated, check operators
            if ($newStatus === 'inactive') {
                $activeOperatorsCount = $toda->operators()->where('status', 'active')->count();
                
                Log::info('Checking active operators', [
                    'toda_id' => $toda->id,
                    'active_operators_count' => $activeOperatorsCount
                ]);

                if ($activeOperatorsCount > 0) {
                    DB::rollBack();
                    return back()->withErrors([
                        'error' => "Cannot deactivate TODA. There are {$activeOperatorsCount} active operators. Please deactivate operators first."
                    ]);
                }
            }

            // Update the TODA status
            $toda->status = $newStatus;
            $toda->save();

            // If deactivating, update related operators
            if ($newStatus === 'inactive') {
                $toda->operators()->update(['status' => 'inactive']);
            }

            DB::commit();

            Log::info('Successfully toggled TODA status', [
                'toda_id' => $toda->id,
                'new_status' => $newStatus
            ]);

            $statusMessage = $newStatus === 'active' ? 'activated' : 'deactivated';
            return back()->with('success', "TODA successfully {$statusMessage}.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error toggling TODA status', [
                'toda_id' => $toda->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'error' => 'An error occurred while updating the TODA status. Please try again.'
            ]);
        }
    }
}
