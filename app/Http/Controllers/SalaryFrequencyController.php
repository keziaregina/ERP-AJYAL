<?php

namespace App\Http\Controllers;

use App\SalaryFrequency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryFrequencyController extends Controller
{
    function index() {
        $businessId = Auth::user()->business_id;

        $salaryCode = SalaryFrequency::where('business_id', $businessId)->get();

        return $salaryCode;
    }
}
