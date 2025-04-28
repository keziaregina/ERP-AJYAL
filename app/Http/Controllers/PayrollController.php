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

class PayrollController extends Controller
{
    /**
     * Generate payroll PDF report
     */
    public function generatePayrollPdf($id)
    {
        Log::info($id);
        try {
            // Get payroll transactions for current month
            // $transactions = Transaction::where('business_id', auth()->user()->business_id)
            //     ->where('type', 'payroll')
            //     ->where('payroll_month', date('m'))
            //     ->with(['transaction_for'])
            //     ->get();

            // // Process transaction data
            // $payrollData = $transactions->map(function ($transaction) {
            //     // Process allowances
            //     $essentials_allowances = json_decode($transaction->essentials_allowances);
            //     $total_allowances = array_sum($essentials_allowances->{'allowance_amounts'});

            //     // Process deductions
            //     $essentials_deductions = json_decode($transaction->essentials_deductions);
            //     $total_deductions = array_sum($essentials_deductions->{'deduction_amounts'});

            //     // Get social security deduction
            //     $socialSecurityAmount = $this->getSocialSecurityAmount($essentials_deductions);

            //     // Get overtime data
            //     $employeeOvertime = $this->getEmployeeOvertime($transaction->transaction_for->id);
                
            //     // Calculate extra hours and working days
            //     $extraHours = array_sum($employeeOvertime->pluck('total_hour')->toArray());
            //     $workingDays = count($employeeOvertime);

            //     // Format data for PDF
            //     return [
            //         'ref_no' => $transaction->id,
            //         'employee_id_type' => 'C',
            //         'employee_id_no' => $transaction->transaction_for->id,
            //         'employee_name' => $transaction->transaction_for->full_name,
            //         'bank_name' => 'BMCE', // Get from bank details if available
            //         'account_number' => $transaction->transaction_for->bank_account_number ?? '',
            //         'salary_freq' => 'M',
            //         'no_of_days' => $workingDays,
            //         'extra_hours' => number_format($extraHours, 2),
            //         'basic_salary' => number_format($transaction->final_total - $total_allowances + $total_deductions, 2),
            //         'extra_income' => number_format($total_allowances, 2),
            //         'social_security_deduction' => number_format($socialSecurityAmount ?? 0, 2),
            //         'net_salary' => number_format($transaction->final_total, 2),
            //         'notes' => '',
            //         'status' => 'Complete'
            //     ];
            // });

            // // Get company bank details
            // $companyBankDetail = CompanyBankDetail::where('business_id', auth()->user()->business_id)
            //     ->first();

            $datas = Transaction::leftJoin('essentials_payroll_group_transactions', 'transactions.id', '=', 'essentials_payroll_group_transactions.transaction_id')
            ->where('essentials_payroll_group_transactions.payroll_group_id', $id)
            ->where('business_id', auth()->user()->business_id)
            ->where('type', 'payroll')
            ->where('payroll_month', date('m'))
            ->with(['transaction_for'])
            ->get();

            $transactionPayrolls = $datas->map(function ($data) {
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
            ->where('month', date('m'))
            ->where('year', date('Y'))
            ->whereNotIn('total_hour', ['A','SL','VL','GE'])
            ->get();

            $data->social_security_deductions = $socialSecurityAmount;
            $data->extra_hours = array_sum($employeeOvertime->pluck('total_hour')->toArray());
            $data->working_days = array_sum($employeeOvertime->pluck('total_hour')->toArray());

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