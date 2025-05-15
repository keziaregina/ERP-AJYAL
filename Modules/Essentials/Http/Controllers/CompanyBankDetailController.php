<?php

namespace Modules\Essentials\Http\Controllers;

use App\Transaction;
use App\EmployeeOvertime;
use App\SifExportCounter;
use App\CompanyBankDetail;
use Illuminate\Http\Request;
use App\Exports\SifExcelExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Essentials\Entities\PayrollGroup;

class CompanyBankDetailController extends Controller {
    function index() {
        $companyBankDetail = CompanyBankDetail::where('business_id', Auth::user()->business_id)->get()->first();
        $employeeTypes = CompanyBankDetail::EMPLOYEE_ID_TYPE;

        return view('essentials::company_bank_detail.index',  compact('companyBankDetail', 'employeeTypes'));
    }
    function store(Request $request) {
        try {
            $request->validate([
                "employer_cr_no" => "required",
                "payer_cr_no" => "required",
                "payer_bank_short_name" => "required",
                "payer_account_number" => "required",
                "employee_type_id" => 'required'
            ]);

            CompanyBankDetail::updateOrCreate([
                'business_id' => Auth::user()->business_id
            ],[
                "employer_cr_no" => $request->employer_cr_no,
                "payer_cr_no" => $request->payer_cr_no,
                "payer_bank_short_name" => $request->payer_bank_short_name,
                "payer_account_number" => $request->payer_account_number,
                'employee_type_id' => $request->employee_type_id
            ]);

            return redirect()->back()->with('success', 'Succeed.');
        } catch (\Exception $e) {
            //throw $th;
            Log::error('ERROR on store company bank detail : '. $e->getMessage());
            throw $e;
        }
    }

    function addSifExportCounter() {
        $sifCount = SifExportCounter::updateOrCreate([
            'business_id' => auth()->user()->business_id,
            'date' => date('Y-m-d')
        ]);
        
        $sifCount->count++;
        $sifCount->save();
        $formattedCount = str_pad($sifCount->count, 3, '0', STR_PAD_LEFT);
        return $formattedCount;
    }
    // Using transaction table
    function exportExcel($id) {
        try {
            $payrollGroup = PayrollGroup::find($id);

            $datas = Transaction::leftJoin('essentials_payroll_group_transactions', 'transactions.id', '=', 'essentials_payroll_group_transactions.transaction_id')
            ->where('essentials_payroll_group_transactions.payroll_group_id', $id)
            ->where('business_id', auth()->user()->business_id)
            ->where('type', 'payroll')
            // ->where('payroll_month', date('m'))
            ->with(['transaction_for'])
            ->get();

            $transactionPayrolls = $datas->map(function ($data) use ($payrollGroup) {
                $essentials_allowances = json_decode($data->essentials_allowances);
                $data->essentials_allowances = array_sum($essentials_allowances->{'allowance_amounts'});
                $essentials_deductions = json_decode($data->essentials_deductions);

                $socialSecurityAmount = null;
                
                foreach ($essentials_deductions->deduction_names as $key => $deduction_name) {
                    if (strpos($deduction_name, 'Social Security Deductions') !== false) {
                        $socialSecurityAmount = $essentials_deductions->deduction_amounts[$key];
                        unset($essentials_deductions->deduction_names[$key]);
                        unset($essentials_deductions->deduction_amounts[$key]);
                        unset($essentials_deductions->deduction_types[$key]);
                        unset($essentials_deductions->deduction_short_names[$key]);
                        unset($essentials_deductions->deduction_percents[$key]);
                        break;
                    }
                }

                $data->essentials_deductions = array_sum($essentials_deductions->{'deduction_amounts'});

                $employeeOvertime = EmployeeOvertime::where('user_id', $data->transaction_for->id)
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

                // $employeeOvertime = EmployeeOvertime::where('user_id', $data->transaction_for->id)
                // ->where('month', date('m'))
                // ->where('year', date('Y'))
                // ->whereNotIn('total_hour', ['A','SL','VL','GE'])
                // ->get();

                $data->social_security_deductions = $socialSecurityAmount;
                $data->extra_hours = array_sum($employeeOvertime->pluck('total_hour')->toArray());
                $data->working_days = now()->month($payrollGroup->payroll_group_month)->daysInMonth - $slRecords - $aRecords - $vlRecords;

                return $data;

            });

            $totalSalary = $transactionPayrolls->sum('final_total');
            $numberOfRecords = $transactionPayrolls->count();

            $companyBankDetail = CompanyBankDetail::where('business_id', auth()->user()->business_id)->get()->first();

            $sifExportCounter = $this->addSifExportCounter();
            $fileFormat = 'SIF_'. $companyBankDetail->employer_cr_no .'_'. $companyBankDetail->payer_bank_short_name . '_' . date('Ymd') . '_' . $sifExportCounter . '.xls';

            return Excel::download(new SifExcelExport(
                transactionPayrolls: $transactionPayrolls,
                companyBankDetail: $companyBankDetail,
                totalSalary: $totalSalary,
                numberOfRecords: $numberOfRecords,
                salaryMonth: $payrollGroup->payroll_group_month,
                salaryYear: $payrollGroup->payroll_group_year
            ), $fileFormat, \Maatwebsite\Excel\Excel::XLS);
        } catch (\Exception $e) {/* The `sif-export-excel` route is used to export data related to company bank details to an Excel file. When this route is accessed, it triggers the `exportExcel` method within the `CompanyBankDetailController` class, which is responsible for generating and downloading the Excel file containing the company bank details data. */
        
            //throw $th;
            Log::error('ERROR on exportExcel : '. $e->getMessage());
            throw $e;
        }
    }
}