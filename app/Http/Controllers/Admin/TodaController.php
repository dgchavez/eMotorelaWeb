<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toda;
use Illuminate\Http\Request;

class TodaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todas = Toda::query()
            ->when(request('search'), function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('president', 'like', "%{$search}%");
            })
            ->when(request('status'), function($query, $status) {
                $query->where('status', $status);
            })
            ->paginate(10);

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
            'toda_name' => 'required|string|max:100|unique:todas,name,' . $toda->id,
            'toda_president' => 'required|string|max:100',
            'registration_date' => 'required|date',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $toda->update([
                'name' => $validated['toda_name'],
                'president' => $validated['toda_president'],
                'registration_date' => $validated['registration_date'],
                'description' => $validated['description'],
                'status' => $validated['status'],
            ]);

            return redirect()->route('toda.index')
                ->with('success', 'TODA updated successfully.');
        } catch (\Exception $e) {
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
            $toda->delete();
            return redirect()->route('toda.index')
                ->with('success', 'TODA deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while deleting the TODA.']);
        }
    }
}
