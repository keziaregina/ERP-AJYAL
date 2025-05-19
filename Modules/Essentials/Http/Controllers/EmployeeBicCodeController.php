<?php

namespace Modules\Essentials\Http\Controllers;

use App\EmployeeBicCode;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class EmployeeBicCodeController extends Controller
{
    function index() {
        $businessId = Auth::user()->business_id;

        $bicCode = EmployeeBicCode::where('business_id', $businessId)->get();

        return $bicCode;
    }
    
}
