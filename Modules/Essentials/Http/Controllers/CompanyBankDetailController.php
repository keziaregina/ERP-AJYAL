<?php

namespace Modules\Essentials\Http\Controllers;

use App\CompanyBankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
}