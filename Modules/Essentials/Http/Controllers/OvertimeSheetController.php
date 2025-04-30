<?php

namespace Modules\Essentials\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Utils\Util;
use App\EmployeeOvertime;
use App\GloriousEmployee;
use App\Utils\ModuleUtil;
use App\Utils\BusinessUtil;
use Illuminate\Http\Request;
use App\Utils\TransactionUtil;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\OvertimeSheetExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;
use Modules\Essentials\Utils\EssentialsUtil;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class OvertimeSheetController extends Controller
{
    public function __construct(
        protected ModuleUtil $moduleUtil,
        protected EssentialsUtil $essentialsUtil,
        protected Util $commonUtil,
        protected TransactionUtil $transactionUtil,
        protected BusinessUtil $businessUtil

        
    ) {
        // $this->middleware('can:essentials.show_overtime_page');
    }

    function index()
    {
        try {
            $businessId = request()->session()->get('user.business_id');
        
            $employees = $this->getActiveEmployeesPerBusiness(businessId: $businessId);

            $daysInMonth = Carbon::now()->month(date('m'))->daysInMonth;
            $overtimeOptions = EmployeeOvertime::OVERTIME_HOURS;
            $findKey = array_search('Glorious Employee Allowance', $overtimeOptions);
            unset($overtimeOptions[$findKey]);

            // Get overtime data for the current month
            $overtimeData = $this->getOvertimeDataForCurrentMonth();
            $overtimeDatas = $overtimeData['employees'];
            $totalAllOvertime = $overtimeData['total_all_overtime'];

            $gloriousEmployeeThisMonth = GloriousEmployee::where('month', date('m'))
            ->where('year', date('Y'))
            ->get()
            ->first();

            // dd($gloriousEmployeeThisMonth);

            return view('essentials::overtime_sheets.index')->with(compact('employees', 'daysInMonth', 'overtimeOptions', 'overtimeDatas', 'totalAllOvertime', 'gloriousEmployeeThisMonth'));
        } catch (\Exception $e) {
            Log::error("error on index overtime: "  . $e->getMessage());
            throw $e;
        }
    }

    function store(Request $request)
    {
        
        try {

            $request->validate([
                'user_id' => 'required|array',
                'user_id.*' => 'required|exists:users,id|distinct',
                'overtime_hours' => ['required', Rule::in(array_keys(EmployeeOvertime::OVERTIME_HOURS))],
                'date' => 'nullable|date'
            ]);

            if ( $request->date != null) {
                $date = $request->date;
                $day = date('d', strtotime($date));
            } else {
                $day = date('d');
            }

            $users = $request->user_id;
            foreach ($users as $user) {
                EmployeeOvertime::updateOrCreate([
                    'user_id' => $user,
                    'day' => $day,
                    'month' => date('m'),
                    'year' => date('Y'),
                ], [
                    'total_hour' => $request->overtime_hours,
                    'created_by' => auth()->id()
                ]);
            }
            

            return redirect()->back()->with('success', 'Created Successfully.');
        } catch (\Exception $e) {
            // Log::error("error storing overtime hour : " . $e->getMessage());
            Log::error("error storing overtime hour : " . $e->getMessage());
            throw $e;
        }
    }

    private function getEmployeesByLocation($businessId, $locationId = null)
    {
        try {
            $query = User::query()
            ->whereHas('roles', function ( Builder $query) {
                $query->whereNotIn('id', [1]);
            });

            if (! empty($locationId)) {
                $query->where('location_id', $locationId);
            } else {
                $query->whereNull('location_id');
            }

            $query = $query->where('business_id', $businessId)
                ->where('status', 'active');

            $employees = $query->select('id', DB::raw("CONCAT(COALESCE(surname, ''),' ',COALESCE(first_name, ''),' ',COALESCE(last_name,'')) as full_name"))->get();

            return $employees;
        } catch (\Exception $e) {
            Log::error("error get employees: " . $e->getMessage());
            throw $e;
        }
    }

    private function getActiveEmployeesPerBusiness($businessId) {

        $rawEmployees = User::forDropdownWithActive(business_id: $businessId);
            
        $employees = collect($rawEmployees)->each(function ($employeeId, $employeName) {
            return [
                'id' => $employeeId,
                'full_name' => $employeName
            ];
        });
        $employees = [];

        foreach ($rawEmployees as $key => $value) {
            $employees[] = [
                'id' => $key,
                'full_name' => $value
            ];
        }
        
        $employees = collect($employees)->filter(function ($employee) {
            return isset($employee['id']) && $employee['id'] != '' && $employee['id'] != null;
        });

        return $employees;
    }

    /**
     * Get overtime data for users in the current month
     * 
     * @return \Illuminate\Support\Collection
     */
    private function getOvertimeDataForCurrentMonth()
    {
        try {
            $currentMonth = date('m');

            $currentYear = date('Y');

            $businessId = request()->session()->get('user.business_id');

            // Get all active employees
            $employees = $this->getActiveEmployeesPerBusiness(businessId: $businessId);

            // Get all overtime records for the current month
            $overtimeRecords = EmployeeOvertime::where('month', $currentMonth)
                ->where('year', $currentYear)
                ->whereIn('user_id', $employees->pluck('id'))
                ->get();

            // Group overtime records by user_id
            $overtimeByUser = $overtimeRecords->groupBy('user_id');

            // Process the data to create a structured format
            $result = $employees->map(function ($employee) use ($overtimeByUser, $currentMonth) {
                $overtimeData = [];

                // Initialize all days with null values
                for ($day = 1; $day <= now()->daysInMonth; $day++) {
                    $overtimeData[str_pad($day, 2, '0', STR_PAD_LEFT)] = null;
                }

                // Fill in the actual overtime data if user has any records
                if ($overtimeByUser->has($employee['id'])) {
                    foreach ($overtimeByUser->get($employee['id']) as $overtime) {
                        $overtimeData[$overtime->day] = $overtime->total_hour;
                    }
                }
                
                // Log::info("EMPLOYEE-------------->");
                // Log::info(json_encode($employee['id'],JSON_PRETTY_PRINT));

                // Log::info("OVERTIME DATA-------------->");
                // Log::info(json_encode($overtimeData,JSON_PRETTY_PRINT));


                $filteredOvertimeData = collect(array_values($overtimeData))->filter(function ($value) {
                    return $value != 'A' && $value != 'VL' && $value != 'GE' && $value != 'SL';
                })->toArray();

                // Calculate total overtime hours properly handling minutes
                $totalOvertimeMonthly = 0;
                $totalHours = 0;
                $totalMinutes = 0;
                
                foreach ($filteredOvertimeData as $overtimeValue) {
                    if (is_numeric($overtimeValue)) {
                        // Split the value into hours and minutes
                        $parts = explode('.', (string)$overtimeValue);
                        $hours = (int)$parts[0];
                        $minutes = isset($parts[1]) ? (int)$parts[1] : 0;
                        
                        // Add to totals
                        $totalHours += $hours;
                        $totalMinutes += $minutes;
                    }
                }
                
                // Convert excess minutes to hours
                $additionalHours = floor($totalMinutes / 60);
                $remainingMinutes = $totalMinutes % 60;
                
                // Calculate final total with proper formatting for minutes
                $totalOvertimeMonthly = $totalHours + $additionalHours + ($remainingMinutes / 100);
                
                // Format to ensure minutes always have two digits
                $totalOvertimeMonthly = number_format($totalOvertimeMonthly, 2, '.', '');

                return [
                    'user_id' => $employee['id'],
                    'full_name' => $employee['full_name'],
                    'overtime_data' => $overtimeData,
                    'total_overtime_by_month' => $totalOvertimeMonthly
                ];
            });

            Log::info(json_encode($result,JSON_PRETTY_PRINT));

            // Calculate total overtime across all employees
            $totalAllOvertime = 0;
            foreach ($result as $employeeData) {
                $totalAllOvertime += (float)$employeeData['total_overtime_by_month'];
            }
            
            // Format the total to ensure minutes have two digits
            $totalAllOvertime = number_format($totalAllOvertime, 2, '.', '');

            Log::info(json_encode($totalAllOvertime,JSON_PRETTY_PRINT));

            // Add the total to the result
            $resultWithTotal = [
                'employees' => $result,
                'total_all_overtime' => $totalAllOvertime
            ];

            Log::info(json_encode($resultWithTotal,JSON_PRETTY_PRINT));

            return $resultWithTotal;
        } catch (\Exception $e) {
            Log::error("error getting overtime data: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get attendance data for payroll calculations
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendanceData(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $month = $request->input('month');
            
            if (!$user_id || !$month) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing required parameters'
                ]);
            }

            // $start_date = Carbon::parse($month)->startOfMonth();
            // $end_date = Carbon::parse($month)->endOfMonth();

            // Get overtime records for the month
            // $overtime_records = EmployeeOvertime::where('user_id', $user_id)
            //     ->whereDate('created_at', '>=', $start_date)
            //     ->whereDate('created_at', '<=', $end_date)
            //     ->get();
            $decimalBreakPoint = Auth::user()->business->currency_precision;
            // $overtimeBonusFee = Auth::user()->business->essentialsAllowanceAndDeductions()->where('description', 'ساعات عمل إضافية Overtime')->first()->amount;
            $allowancesDeducDatas = Auth::user()->business->essentialsAllowanceAndDeductions->pluck('amount', 'description');

            $finalAllowanceDeduct = [];
            foreach ($allowancesDeducDatas as $desc => $amount) {
                // $finalAllowanceDeduct['overtime_fee'] = 
                switch ($desc) {
                    case 'ساعات عمل إضافية Overtime':
                        $finalAllowanceDeduct['overtime_fee'] = $amount;
                        break;
                    case 'علاوة أكل Food Allowances':
                        $finalAllowanceDeduct['food_allowance_1'] = $amount;
                        break;
                    case 'علاوة أكل-Food Allowances':
                        $finalAllowanceDeduct['food_allowance_2'] = $amount;
                        break;
                    case 'Social Security Deductions الحماية الإجتماعية':
                        $finalAllowanceDeduct['social_security'] = $amount;
                        break;
                    case 'Glorious employee allowance (GE) الموظف المجيد':
                        $finalAllowanceDeduct['ge_amount'] = $amount;
                        break;
                }
            }

            $isGloriousEmployee = GloriousEmployee::where('month', date('m'))
            ->where('year', date('Y'))
            ->where('user_id', $user_id)
            ->first();

            Log::info(date('m'));
            Log::info(date('Y'));
            Log::info($user_id);

            Log::info(json_encode($isGloriousEmployee,JSON_PRETTY_PRINT));

            $overtime_records = EmployeeOvertime::where('user_id', $user_id)
                ->where('month', date('m'))
                ->where('year', date('Y'))
                // ->whereDate('created_at', '<=', $end_date)
                ->get();

            $overtime_hours = EmployeeOvertime::where('user_id', $user_id)
                ->where('month', date('m'))
                ->where('year', date('Y'))
                ->whereNotIn('total_hour', ['A','VL','SL','GE'])
                ->pluck('total_hour')
                ->toArray();

            // Calculate total overtime hours
            $total_overtime = $this->calculateTotalOvertime($overtime_hours);

            $absent_days = 0;
            $vacation_days = 0;
            $sick_leave_days = 0;
            // $glorious_employee = false;
            $glorious_employee = $isGloriousEmployee ? true : false;

            foreach ($overtime_records as $record) {
                switch ($record->total_hour) {
                    case 'A':
                        $absent_days++;
                        break;
                    case 'VL':
                        $vacation_days++;
                        break;
                    case 'SL':
                        $sick_leave_days++;
                        break;
                    case 'GE':
                        $glorious_employee = true;
                        break;
                }
            }

            Log::info(json_encode([
                'success' => true,
                'overtime_hours' => $total_overtime,
                'absent_days' => $absent_days,
                'vacation_days' => $vacation_days,
                'sick_leave_days' => $sick_leave_days,
                'glorious_employee' => $glorious_employee,
                ...$finalAllowanceDeduct
            ],JSON_PRETTY_PRINT));

            return response()->json([
                'success'            => true,
                'overtime_hours'     => $total_overtime,
                'absent_days'        => $absent_days,
                'vacation_days'      => $vacation_days,
                'sick_leave_days'    => $sick_leave_days,
                'glorious_employee'  => $glorious_employee,
                'decimal_breakpoint' => $decimalBreakPoint,
                ...$finalAllowanceDeduct
                // 'overtime_fee'       => $overtimeBonusFee
            ]);

        } catch (\Exception $e) {
            Log::error("Error getting attendance data: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving attendance data'
            ]);
        }
    }

    /**
     * Calculate total overtime hours from array of time values
     * Handles format like "2.30" (2 hours 30 minutes)
     * 
     * @param array $overtime_hours
     * @return float
     */
    private function calculateTotalOvertime($overtime_hours)
    {
        $total_hours = 0;
        $total_minutes = 0;

        foreach ($overtime_hours as $time) {
            // Convert to string and remove any quotes or special characters
            $time = str_replace(['"', "'"], '', (string)$time);
            
            if (is_numeric($time)) {
                // Split the time into hours and decimal part
                $parts = explode('.', $time);
                
                // Add hours
                $total_hours += intval($parts[0]);

                // If there's a decimal part, convert it to minutes
                if (isset($parts[1])) {
                    // Convert decimal part to actual minutes (e.g., .30 → 30 minutes)
                    $minutes = intval($parts[1]);
                    if ($minutes < 10) {
                        $minutes *= 10; // Handle single digit decimals (e.g., .3 → 30 minutes)
                    }
                    $total_minutes += $minutes;
                }
            }
        }

        // Convert excess minutes to hours
        $additional_hours = floor($total_minutes / 60);
        $remaining_minutes = $total_minutes % 60;

        // Calculate final total in decimal format
        $total = $total_hours + $additional_hours + ($remaining_minutes / 60);

        return round($total, 2);
    }

    public function exportPdf()
    {
        try {
            $logo = public_path('img/logo-small.png');
            $overtimeData = $this->getOvertimeDataForCurrentMonth();
            $data = $overtimeData['employees'];
            $totalAllOvertime = $overtimeData['total_all_overtime'];
            
            $pdf = PDF::loadView('essentials::overtime_sheets.pdf', [
                'data' => $data,
                'totalAllOvertime' => $totalAllOvertime,
                'logo' => $logo,
                'business' => request()->session()->get('business'),
                'location' => request()->session()->get('user.location_id'),
                'month' => now()->format('F'),
                'year' => now()->format('Y'),
            ], [], [
                'orientation' => 'L',
                'format' => 'A4'
            ]);

            return $pdf->download('overtime_report-' . now()->format('F_Y') . '.pdf');
            
        } catch (\Exception $e) {
            Log::error("Error generating overtime PDF: " . $e->getMessage());
            return back()->with('error', 'Could not generate PDF. Please try again.');
        }
    }

    public function exportExcel()
    {   
        $overtimeData = $this->getOvertimeDataForCurrentMonth();
        $data = $overtimeData['employees'];
        $totalAllOvertime = $overtimeData['total_all_overtime'];
        
        return Excel::download(new OvertimeSheetExport(
                $data, 
                now()->format('F'), 
                now()->format('Y'), 
                $totalAllOvertime
            ), 
            'overtime_sheet-'.now()->format('F_Y').'.xlsx'
        );        
    }

    public function getAllowDeduct(Request $request) {
        try {
            Log::info("business id");
            Log::info(Auth::user()->business_id);
            $employeeAllowDeduct = $this->essentialsUtil->getEmployeeAllowancesAndDeductions(
                business_id: Auth::user()->business_id,
                user_id: $request->user_id,
                start_date: Carbon::now()->startOfMonth(),
                end_date: Carbon::now()->endOfMonth()
            );

            $payrolls = [];

            $allowances = [];
            $deductions = [];

            foreach ($employeeAllowDeduct as $ad) {
                Log::info("ad----------->");
                Log::info(json_encode($ad,JSON_PRETTY_PRINT));

                if ($ad->type == 'allowance') {
                    $allowances[] = [
                        'name' => $ad->description,
                        'amount' => $ad->amount,
                        'amount_type' => $ad->amount_type == 'percent' ? 'Percentage' : 'Fixed',
                    ];
                } else {
                    $deductions[] = [
                        'name' => $ad->description,
                        'amount' => $ad->amount,
                        'amount_type' => $ad->amount_type == 'percent' ? 'Percentage' : 'Fixed',
                    ];
                }
            }

            $allowances = array_filter($allowances, function ($allowance) {
                return !str_contains($allowance['name'], 'Overtime');
            });

            $deductions = array_filter($deductions, function ($deduction) {
                return !str_contains($deduction['name'], 'Absant');
            });

            return [
                'allowances' => $allowances,
                'deductions' => $deductions
            ];


        } catch (\Exception $exception) {
            Log::error("ERROR on get allow deduct data -> " . $exception->getMessage());
            throw $exception;
        }
    }
}
