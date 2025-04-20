<?php

namespace Modules\Essentials\Http\Controllers;

use App\User;
use Carbon\Carbon;
use App\Utils\Util;
use App\EmployeeOvertime;
use App\Utils\ModuleUtil;
use App\Utils\BusinessUtil;
use Illuminate\Http\Request;
use App\Utils\TransactionUtil;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exports\OvertimeSheetExport;
use App\Http\Controllers\Controller;
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
    ) {}

    function index()
    {
        try {
            // dd(Carbon::now()->subDays(8));
            $businessId = request()->session()->get('user.business_id');
            // $employees = $this->getEmployeesByLocation(businessId: $businessId);
        
            $employees = $this->getActiveEmployeesPerBusiness(businessId: $businessId);

            $daysInMonth = Carbon::now()->month(date('m'))->daysInMonth;
            $overtimeOptions = EmployeeOvertime::OVERTIME_HOURS;

            // Get overtime data for the current month
            $overtimeData = $this->getOvertimeDataForCurrentMonth();
            $overtimeDatas = $overtimeData['employees'];
            $totalAllOvertime = $overtimeData['total_all_overtime'];

            return view('essentials::overtime_sheets.index')->with(compact('employees', 'daysInMonth', 'overtimeOptions', 'overtimeDatas', 'totalAllOvertime'));
        } catch (\Exception $e) {
            Log::error("error on index overtime: "  . $e->getMessage());
            throw $e;
        }
    }

    function store(Request $request)
    {
        
        try {

            // dd($request->all());

            $request->validate([
                'user_id' => 'required|exists:users,id',
                'overtime_hours' => ['required', Rule::in(array_keys(EmployeeOvertime::OVERTIME_HOURS))]
            ]);

            $overtimeHour = EmployeeOvertime::updateOrCreate([
                'user_id' =>  $request->user_id,
                'day' => date('d'),
                'month' => date('m'),
                'year' => date('Y'),
            ], [
                'total_hour' => $request->overtime_hours,
                'created_by' => auth()->id()
            ]);

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
        return Excel::download(new OvertimeSheetExport($data, $totalAllOvertime), 'overtime_sheet-'.now()->format('F_Y').'.xlsx');        
    }
}
