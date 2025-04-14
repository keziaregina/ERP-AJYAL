<?php

namespace Modules\Essentials\Http\Controllers;

use App\User;
use App\Utils\Util;
use App\Utils\ModuleUtil;
use App\Utils\BusinessUtil;
use App\Utils\TransactionUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\Essentials\Utils\EssentialsUtil;
use Illuminate\Contracts\Database\Query\Builder;

class OvertimeSheetController extends Controller {
    public function __construct(
        protected ModuleUtil $moduleUtil,
        protected EssentialsUtil $essentialsUtil,
        protected Util $commonUtil,
        protected TransactionUtil $transactionUtil,
        protected BusinessUtil $businessUtil
    ) {
    }

    function index() {
        try {
            $businessId = request()->session()->get('user.business_id');
            $employees = $this->getEmployeesByLocation(businessId: $businessId);
            $daysInMonth = Carbon::now()->month(date('m'))->daysInMonth;

            return view('essentials::overtime_sheets.index')->with(compact('employees', 'daysInMonth'));
        } catch (\Exception $e) {
            Log::error("error on index overtime: "  . $e->getMessage());
            throw $e;
        }
    }

    private function getEmployeesByLocation($businessId, $locationId = null) {
        try {
            $query = User::query();

            if (! empty($locationId)) {
                $query->where('location_id', $locationId);
            } else {
                $query->whereNull('location_id');
            }

            $query = $query->where('business_id', $businessId)
            ->where('status', 'active');

            $employees = $query->select('id', DB::raw("CONCAT(COALESCE(surname, ''),' ',COALESCE(first_name, ''),' ',COALESCE(last_name,'')) as full_name"))->get();

            // dd(date('M'));
            // dd($daysInMonth);

            // $employees = $users->pluck('id', 'full_name')->toArray();
            // $employees = $users;

            // Log::info(json_encode($employees,JSON_PRETTY_PRINT));

            return $employees;

        } catch (\Exception $e) {
            Log::error("error get employees: " . $e->getMessage());
            throw $e;
        }
    }
}