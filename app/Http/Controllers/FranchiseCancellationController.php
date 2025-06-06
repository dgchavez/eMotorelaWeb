<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Models\FranchiseCancellation;
use Illuminate\Http\Request;
use App\Services\DocumentGenerationService;

class FranchiseCancellationController extends Controller
{
    protected $documentService;

    public function __construct(DocumentGenerationService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function create(Operator $operator)
    {
        return view('franchise-cancellations.create', compact('operator'));
    }

    public function store(Request $request, Operator $operator)
    {
        $validated = $request->validate([
            'or_number' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'cancellation_date' => 'required|date',
            'reason' => 'nullable|string'
        ]);

        // Create franchise cancellation record
        $cancellation = $operator->franchiseCancellation()->create($validated);

        // Update operator status and cancellation date
        $operator->update([
            'status' => 'inactive',
            'franchise_cancelled_at' => $validated['cancellation_date']
        ]);

        // Generate cancellation certificate
        try {
            $pdfUrl = $this->documentService->generateCancellationCertificate($operator);
            return redirect()->route('operators.show', $operator)
                ->with('success', 'Franchise cancelled successfully. Download the certificate.')
                ->with('pdf_url', $pdfUrl);
        } catch (\Exception $e) {
            return redirect()->route('operators.show', $operator)
                ->with('success', 'Franchise cancelled successfully.')
                ->with('warning', 'Failed to generate cancellation certificate: ' . $e->getMessage());
        }
    }
} 