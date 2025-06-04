<?php

namespace App\Http\Controllers;

use App\Models\Operator;
use App\Services\DocumentGenerationService;
use Illuminate\Http\Request;
use Storage;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentGenerationService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function preview(string $type, Operator $operator)
    {
        try {
            switch ($type) {
                case 'franchise-certificate':
                    return view('documents.franchise-certificate-preview', [
                        'operator' => $operator->load(['toda', 'motorcycles']),
                        'certificateNumber' => 'PREVIEW-' . now()->format('YmdHis'),
                        'issueDate' => now(),
                        'validUntil' => now()->addYears(3),
                    ]);

                case 'motorela-permit':
                    return view('documents.motorela-permit-preview', [
                        'operator' => $operator->load(['toda', 'motorcycles', 'drivers']),
                        'permitNumber' => 'PREVIEW-' . now()->format('YmdHis'),
                        'issueDate' => now(),
                        'validUntil' => now()->addYear(),
                    ]);

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid document type'
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating preview: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateFranchiseCertificate(Operator $operator)
    {
        try {
            $pdfUrl = $this->documentService->generateFranchiseCertificate($operator);
            return response()->json([
                'success' => true,
                'url' => $pdfUrl,
                'message' => 'Franchise Certificate generated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating Franchise Certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateMotorelaPermit(Operator $operator)
    {
        try {
            $pdfUrl = $this->documentService->generateMotorelaPermit($operator);
            return response()->json([
                'success' => true,
                'url' => $pdfUrl,
                'message' => 'Motorela Permit generated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating Motorela Permit: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateMonthlyReport(Request $request)
    {
        try {
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));
            
            $pdfUrl = $this->documentService->generateMonthlyReport($month, $year);
            return response()->json([
                'success' => true,
                'url' => $pdfUrl,
                'message' => 'Monthly report generated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating Monthly Report: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generate(string $type, Operator $operator)
    {
        try {
            $pdfUrl = null;
            
            switch ($type) {
                case 'franchise-certificate':
                    $pdfUrl = $this->documentService->generateFranchiseCertificate($operator);
                    break;
                case 'motorela-permit':
                    $pdfUrl = $this->documentService->generateMotorelaPermit($operator);
                    break;
                default:
                    \Log::error('Invalid document type requested', [
                        'type' => $type,
                        'operator_id' => $operator->id
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid document type'
                    ], 400);
            }

            if (!$pdfUrl) {
                throw new \Exception('PDF URL is null');
            }

            // Get the relative path from the URL
            $relativePath = parse_url($pdfUrl, PHP_URL_PATH);
            $relativePath = ltrim($relativePath, '/storage/');

            // Check if file exists in storage
            if (!Storage::disk('public')->exists($relativePath)) {
                \Log::error('Generated PDF file not found', [
                    'path' => $relativePath,
                    'type' => $type,
                    'operator_id' => $operator->id
                ]);
                throw new \Exception('Generated file not found');
            }

            return response()->json([
                'success' => true,
                'url' => $pdfUrl,
                'message' => ucfirst(str_replace('-', ' ', $type)) . ' generated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error generating document', [
                'error' => $e->getMessage(),
                'type' => $type,
                'operator_id' => $operator->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error generating document: ' . $e->getMessage()
            ], 500);
        }
    }
} 