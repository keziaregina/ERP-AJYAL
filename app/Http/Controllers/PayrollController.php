<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Transaction;
use App\EmployeeOvertime;
use App\CompanyBankDetail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Essentials\Entities\PayrollGroup;

class PayrollController extends Controller
{
    /**
     * Generate payroll PDF report
     */
    public function generatePayrollPdf($id)
    {
        try {
            $payrollGroup = PayrollGroup::find($id);

            $datas = Transaction::leftJoin('essentials_payroll_group_transactions', 'transactions.id', '=', 'essentials_payroll_group_transactions.transaction_id')
            ->where('essentials_payroll_group_transactions.payroll_group_id', $id)
            ->where('business_id', auth()->user()->business_id)
            ->where('type', 'payroll')
            // ->where('payroll_month', date('m'))
            ->with(['transaction_for'])
            ->get();

            $transactionPayrolls = $datas->map(function ($data) use ($payrollGroup){
            $essentials_allowances = json_decode($data->essentials_allowances);
            $data->essentials_allowances = array_sum($essentials_allowances->{'allowance_amounts'});
            $essentials_deductions = json_decode($data->essentials_deductions);
            $data->essentials_deductions = array_sum($essentials_deductions->{'deduction_amounts'});

            $socialSecurityAmount = null;

            foreach ($essentials_deductions->deduction_names as $key => $deduction_name) {
            if (strpos($deduction_name, 'Social Security Deductions') !== false) {
            $socialSecurityAmount = $essentials_deductions->deduction_amounts[$key];
            break;
            }
            }


            $employeeOvertime = EmployeeOvertime::where('user_id', $data->transaction_for->id)
            // ->where('month', date('m'))
            // ->where('year', date('Y'))
            ->where('month', $payrollGroup->payroll_group_month)
            ->where('year', $payrollGroup->payroll_group_year)
            ->whereNotIn('total_hour', ['A','SL','VL','GE'])
            ->get();

            $slRecords = EmployeeOvertime::where('user_id', $data->transaction_for->id)
            ->where('month', $payrollGroup->payroll_group_month)
            ->where('year', $payrollGroup->payroll_group_year)
            ->where('total_hour', 'SL')
            ->count();

            $vlRecords = EmployeeOvertime::where('user_id', $data->transaction_for->id)
            ->where('month', $payrollGroup->payroll_group_month)
            ->where('year', $payrollGroup->payroll_group_year)
            ->where('total_hour', 'VL')
            ->count();

            $aRecords = EmployeeOvertime::where('user_id', $data->transaction_for->id)
            ->where('month', $payrollGroup->payroll_group_month)
            ->where('year', $payrollGroup->payroll_group_year)
            ->where('total_hour', 'A')
            ->count();

            $data->social_security_deductions = $socialSecurityAmount;
            $data->extra_hours = array_sum($employeeOvertime->pluck('total_hour')->toArray());
            $data->working_days = now()->month($payrollGroup->payroll_group_month)->daysInMonth - $slRecords - $aRecords - $vlRecords;

            return $data;

            });

            $totalSalary = $transactionPayrolls->sum('final_total');
            $numberOfRecords = $transactionPayrolls->count();

            $companyBankDetail = CompanyBankDetail::where('business_id', auth()->user()->business_id)->get()->first();


            // Prepare data for PDF
            $data = [
                'companyBankDetail' => $companyBankDetail,
                'totalSalary' => $totalSalary,
                'numberOfRecords' => $numberOfRecords,
                'payrollData' => $transactionPayrolls
            ];

            // dd($data);
            // Generate PDF with specific options
            $pdf = PDF::loadView('payroll.payroll_pdf', $data)
                ->setPaper('a4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'isRemoteEnabled' => true,
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'margin_top' => 10,
                    'margin_bottom' => 10
                ]);
            
            // Generate filename
            $filename = 'Payroll_Report_' . date('Ymd') . '.pdf';

            // Return PDF for download
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error("Error generating payroll PDF: " . $e->getMessage());
            return back()->with('error', 'Error generating payroll PDF: ' . $e->getMessage());
        }
    }

    /**
     * Get social security deduction amount
     */
    private function getSocialSecurityAmount($deductions)
    {
        foreach ($deductions->deduction_names as $key => $deduction_name) {
            if (strpos($deduction_name, 'Social Security Deductions') !== false) {
                return $deductions->deduction_amounts[$key];
            }
        }
        return null;
    }

    /**
     * Get employee overtime records
     */
    private function getEmployeeOvertime($userId)
    {
        return EmployeeOvertime::where('user_id', $userId)
            ->where('month', date('m'))
            ->where('year', date('Y'))
            ->whereNotIn('total_hour', ['A','SL','VL','GE'])
            ->get();
    }
} 