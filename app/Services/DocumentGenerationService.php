<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Operator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Storage;

class DocumentGenerationService
{
    /**
     * Generate franchise certificate for an operator
     */
    public function generateFranchiseCertificate(Operator $operator)
    {
        try {
            $data = [
                'operator' => $operator->load(['toda', 'motorcycles']),
                'certificateNumber' => $this->generateCertificateNumber(),
                'issueDate' => Carbon::now(),
                'validUntil' => Carbon::now()->addYears(3),
            ];

            $pdf = PDF::loadView('documents.franchise-certificate', $data);
            $pdf->setPaper('a4', 'portrait');
            
            $filename = 'franchise_certificates/' . $operator->id . '_' . time() . '.pdf';
            
            // Save PDF using Storage facade
            Storage::disk('public')->put($filename, $pdf->output());
            
            // Verify file exists
            if (!Storage::disk('public')->exists($filename)) {
                throw new \Exception('Failed to save PDF file');
            }
            
            return Storage::disk('public')->url($filename);
        } catch (\Exception $e) {
            \Log::error('Error generating franchise certificate', [
                'error' => $e->getMessage(),
                'operator_id' => $operator->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generate Motorela permit for an operator
     */
    public function generateMotorelaPermit(Operator $operator)
    {
        try {
            $data = [
                'operator' => $operator->load(['toda', 'motorcycles', 'drivers']),
                'permitNumber' => $this->generatePermitNumber(),
                'issueDate' => Carbon::now(),
                'validUntil' => Carbon::now()->addYear(),
            ];

            $pdf = PDF::loadView('documents.motorela-permit', $data);
            $pdf->setPaper('a4', 'portrait');
            
            $filename = 'motorela_permits/' . $operator->id . '_' . time() . '.pdf';
            
            // Save PDF using Storage facade
            Storage::disk('public')->put($filename, $pdf->output());
            
            // Verify file exists
            if (!Storage::disk('public')->exists($filename)) {
                throw new \Exception('Failed to save PDF file');
            }
            
            return Storage::disk('public')->url($filename);
        } catch (\Exception $e) {
            \Log::error('Error generating motorela permit', [
                'error' => $e->getMessage(),
                'operator_id' => $operator->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generate monthly report of applications
     */
    public function generateMonthlyReport($month, $year)
    {
        $applications = Application::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->with(['operator.toda'])
            ->get()
            ->groupBy('status');

        $data = [
            'applications' => $applications,
            'month' => Carbon::createFromDate($year, $month, 1)->format('F Y'),
            'totalApplications' => $applications->flatten()->count(),
            'statistics' => [
                'approved' => $applications->get(Application::STATUS_APPROVED, collect())->count(),
                'rejected' => $applications->get(Application::STATUS_REJECTED, collect())->count(),
                'pending' => $applications->get(Application::STATUS_PENDING, collect())->count(),
            ]
        ];

        $pdf = PDF::loadView('documents.monthly-report', $data);
        
        $filename = 'reports/monthly_' . $year . '_' . $month . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());
        
        return Storage::disk('public')->url($filename);
    }

    /**
     * Generate a unique certificate number
     */
    private function generateCertificateNumber(): string
    {
        return 'FC-' . date('Y') . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a unique permit number
     */
    private function generatePermitNumber(): string
    {
        return 'MP-' . date('Y') . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }
} 