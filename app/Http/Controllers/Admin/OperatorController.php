<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\TodaMembership;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function index(Request $request)
    {
        $query = Operator::query()->with('toda');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('mtop_no', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('plate_no', 'like', "%{$search}%");
            });
        }

        // TODA filter
        if ($request->has('toda') && $request->toda !== '') {
            $query->where('toda_id', $request->toda);
        }

        $operators = $query->latest()->paginate(10);
        $todas = TodaMembership::all();

        return view('admin.operatorsIndex', compact('operators', 'todas'));
    }
}
