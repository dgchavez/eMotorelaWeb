<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toda;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TodaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Toda::query()
            ->withCount('operators') // Add count of operators for each TODA
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
            })
            ->when($request->filled('date_from'), function($query) use ($request) {
                $query->whereDate('registration_date', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function($query) use ($request) {
                $query->whereDate('registration_date', '<=', $request->date_to);
            });

        // Add sorting functionality
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');
        $query->orderBy($sort, $direction);

        $todas = $query->paginate(10)->withQueryString();

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

            $toda->update([
                'name' => $validated['toda_name'],
                'president' => $validated['toda_president'],
                'registration_date' => $validated['registration_date'],
                'description' => $validated['description'],
                'status' => $validated['status'],
            ]);

            // If TODA is marked as inactive, update related operators
            if ($validated['status'] === 'inactive') {
                // You might want to handle operators when TODA becomes inactive
                // For example, notify operators or mark them as inactive
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

            // Check if TODA has active operators
            $activeOperatorsCount = $toda->operators()->where('status', 'active')->count();
            
            if ($activeOperatorsCount > 0) {
                return back()->withErrors([
                    'error' => 'Cannot delete TODA. There are still ' . $activeOperatorsCount . ' active operators associated with this TODA.'
                ]);
            }

            // Soft delete the TODA and related records
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
}
