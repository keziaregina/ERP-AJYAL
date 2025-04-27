<?php

namespace Modules\Essentials\Http\Controllers;

use App\Transaction;
use App\CompanyBankDetail;
use Illuminate\Http\Request;
use App\Exports\SifExcelExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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


    // Using transaction table
    function exportExcel() {
        try {
            $transactionPayrolls = Transaction::where('business_id', auth()->user()->business_id)
                                                ->where('type', 'payroll')
                                                ->where('payroll_month', date('m'))
                                                ->with(['transaction_for'])
                                                ->get()->toArray();
                                                // ->get();

            // $totalSalary = $transactionPayrolls->sum('final_total');
            // $numberOfRecords = $transactionPayrolls->count();

            // dd($totalSalary);
            dd($transactionPayrolls);
            $companyBankDetail = CompanyBankDetail::where('business_id', auth()->user()->business_id)->get()->first();

            dd($companyBankDetail->toArray());
            // Log::info(json_encode($transactionPayrolls, JSON_PRETTY_PRINT));

            return Excel::download(new SifExcelExport($transactionPayrolls, $companyBankDetail, $totalSalary, $numberOfRecords), 'sif_excel.xlsx');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}