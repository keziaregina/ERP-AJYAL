<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyBankDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_cr_no',
        'payer_cr_no',
        'payer_bank_short_name',
        'payer_account_number',
        'business_id',
        'employee_type_id'
    ];

    CONST EMPLOYEE_ID_TYPE = [
        'C' => 'Civil Number',
        'P' => 'Passport Number'
    ];
}
