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
        try {
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
            // $data->working_days = array_sum($employeeOvertime->pluck('total_hour')->toArray());
            $data->working_days = $employeeOvertime->count();

            return $data;

            });

            $totalSalary = $transactionPayrolls->sum('final_total');
            $numberOfRecords = $transactionPayrolls->count();
            $logo = public_path('img/logo-small.png');
            $companyBankDetail = CompanyBankDetail::where('business_id', auth()->user()->business_id)->get()->first();
            $createPdfDate = date('d-m-Y');
            $user = auth()->user()->first_name;
            $month = $transactionPayrolls->first()->payroll_month ?? '01';
            $monthName = date('F', mktime(0, 0, 0, $month, 10));
            $year = date('Y');


            // Prepare data for PDF
            $data = [
                'companyBankDetail' => $companyBankDetail,
                'totalSalary' => $totalSalary,
                'numberOfRecords' => $numberOfRecords,
                'payrollData' => $transactionPayrolls,
                'logo' => $logo,
                'createPdfDate' => $createPdfDate,
                'user' => $user,
                'monthName' => $monthName,
                'year' => $year,
            ];

            // dd($data);
    
            // return view ('payroll.payroll_pdf', $data);
            
            // Generate PDF with specific options
            $pdf = PDF::loadView('payroll.payroll_pdf', $data, [
                'orientation' => 'L',
                'format' => 'A4'                
            ]);

            // $pdf = PDF::loadView('payroll.payroll_pdf', $data)
            //     ->setPaper('a4', 'landscape')
            //     ->setOptions([
            //         'defaultFont' => 'DejaVu Sans',
            //         'isRemoteEnabled' => true,
            //         'isHtml5ParserEnabled' => true,
            //         'isPhpEnabled' => true,
            //         'margin_left' => 10,
            //         'margin_right' => 10,
            //         'margin_top' => 10,
            //         'margin_bottom' => 10
            //     ]);
            
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