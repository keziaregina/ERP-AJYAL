<?php

namespace App\Exports;

use App\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class SifExcelExport implements FromView
{
    public $transactionPayrolls;
    public $companyBankDetail;
    public $totalSalary;
    public $salaryMonth;
    public $salaryYear;
    public $numberOfRecords;
    public function __construct($transactionPayrolls, $companyBankDetail, $totalSalary, $numberOfRecords, $salaryMonth, $salaryYear) {
        $this->transactionPayrolls = $transactionPayrolls;
        $this->companyBankDetail = $companyBankDetail;
        $this->totalSalary = $totalSalary;
        $this->numberOfRecords = $numberOfRecords;
        $this->salaryMonth = $salaryMonth;
        $this->salaryYear = $salaryYear;
    }   

    public function view(): View
    {
        return view('essentials::company_bank_detail.exports.sif_excel', [
            'transactionPayrolls' => $this->transactionPayrolls,
            'companyBankDetail'   => $this->companyBankDetail,
            'salaryMonth'         => $this->salaryMonth,
            'salaryYear'          => $this->salaryYear,
            'totalSalary'         => $this->totalSalary,
            'numberOfRecords'     => $this->numberOfRecords
        ]);
    }
}
