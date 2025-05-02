<?php

namespace App\Services;

use App\User;
use App\Contact;
use App\TaxRate;
use App\Transaction;
use App\PurchaseLine;
use App\Mail\Reporting;
use App\EmployeeOvertime;
use App\Utils\ModuleUtil;
use App\SellingPriceGroup;
use App\Utils\ProductUtil;
use App\TransactionPayment;
use App\Utils\BusinessUtil;
use App\TransactionSellLine;
use App\Utils\TransactionUtil;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\SendReportEmailJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use App\TransactionSellLinesPurchaseLines;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as pdh; 
use Modules\Essentials\Http\Controllers\OvertimeSheetController;

class ReportEmailService
{
    public $transactionUtil;
    public $productUtil;
    public $businessUtil;
    public $logo;
    public $moduleUtil;
    public function __construct(TransactionUtil $transactionUtil, ProductUtil $productUtil, BusinessUtil $businessUtil, ModuleUtil $moduleUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->productUtil = $productUtil;
        $this->businessUtil = $businessUtil;
        $this->moduleUtil = $moduleUtil;
        $this->logo = public_path('img/logo-small.png');
    }

    public function generateReportAttachment($data,$dates,$interval)
    {
        $report = null;
            
        $filename = "pdf/report/{$data->user_id}_{$data->type}_" . now()->format('Ymd_His') . ".pdf";
        $user = User::find($data->user_id);
        $directory = dirname($filename);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // dd($data->type);

        switch ($data->type) {
            
            // DONE (need table)
            case 'profit_or_loss_report':
                $type = 'profit_or_loss';
                $data['report_type'] = 'Profit / Loss';
                $report = $this->getProfitOrLossReport($user, $dates['start_date'], $dates['end_date']);
                break;
                
            // DONE
            case 'purchase_n_sell_report':
                $type = 'purchase_n_sale';
                $data['report_type'] = 'Purchase & Sales Summary';
                $report = $this->getPurchaseSellReport($user, $dates['start_date'], $dates['end_date']);
                break;

            // DONE (Need check in last 3 headers )
            case 'tax_report':
                $type = 'tax';
                $data['report_type'] = 'Tax';
                $report = $this->getTaxReport($user, $dates['start_date'], $dates['end_date']);
                break;

                
            // DONE
            case 'customer_n_supplier_report':
                $type = 'customer_n_supplier';
                $data['report_type'] = 'Customer & Supplier';
                $report = $this->getCustomerSupplierReport($user, $dates['start_date'], $dates['end_date']);
                break;

            // DONE
            case 'customer_group_report':
                $type = 'customer_group';
                $data['report_type'] = 'Customer Group';
                $report = $this->getCustomerGroupReport($user, $dates['start_date'], $dates['end_date']);
                break;

            // DONE
            case 'stock_report':
                $type = 'stock';
                $data['report_type'] = 'Stocks Summary';
                    $report = [
                        'stock_report' => $this->getStockReport($user, $dates['start_date'], $dates['end_date']),
                        'stock_value' => $this->getStockValue($user, $dates['start_date'], $dates['end_date']),
                    ];
                break;

            // DONE
            case 'stock_adjustment_report':
                $type = 'stock_adjustment';
                $data['report_type'] = 'Stock Adjustment';
                $report = $this->getStockAdjustmentReport($user, $dates['start_date'], $dates['end_date']);
                break;

            // DONE
            case 'trending_product_report':
                $type = 'trending_product';
                $data['report_type'] = 'Trending Product';
                $report = $this->getTrendingProducts($user, $dates['start_date'], $dates['end_date']);
                break;

            // DONE
            case 'items_report':
                $type = 'items_report';
                $data['report_type'] = 'Items Report';
                $report = $this->getItemsReport($user, $dates['start_date'], $dates['end_date']);
                break;

            // DONE
            case 'product_purchase_report':
                $type = 'product_purchase';
                $data['report_type'] = 'Product Purchase Report';
                $report = $this->getProductPurchaseReport($user, $dates['start_date'], $dates['end_date']);
                break;

            // DONE
            case 'product_sell_report':
                $type = 'product_sell';
                $data['report_type'] = 'Product Sell Report';
                $report = $this->getProductSellReport($user, $dates['start_date'], $dates['end_date']);
                break;

            // DONE
            case 'purchase_payment_report':
                $type = 'purchase_payment';
                $data['report_type'] = 'Purchase Payment Report';
                $report = $this->getPurchasePaymentReport($user, $dates['start_date'], $dates['end_date']);
                break;

            // DONE
            case 'sales_representative':
                $type = 'sales_representative';
                $data['report_type'] = 'Sales Representative Summary';
                    $report = [
                        'overall' => [
                            'sell' => $this->getSalesRepresentativeTotalSell($user, $dates['start_date'], $dates['end_date'], null),
                            'expense' => $this->getSalesRepresentativeTotalExpense($user, $dates['start_date'], $dates['end_date'], null)
                        ],
                        'collection' => [
                            'expense' => $this->getSalesRepresentativeExpenseCollection($user, $dates['start_date'], $dates['end_date'], null)->get(),
                            'sales' => $this->getSalesRepresentativeSalesCollection($user, $dates['start_date'], $dates['end_date'], null)->get(),
                        ]
                    ];
                break;
            // DONE
            case 'register_report':
                $type = 'register';
                $data['report_type'] = 'Register';
                $report = $this->getRegisterReport($user, $dates['start_date'], $dates['end_date'])->get();
                break;

            // DONE
            case 'expense_report':
                $type = 'expense';
                $data['report_type'] = 'Expenses Summary';
                $report = $this->getExpenseReport($user, $dates['start_date'], $dates['end_date']);
                break;

            // DONE
            case 'activity_log':
                $type = 'activity_log';
                $data['report_type'] = 'Activity Log';
                $report = $this->getActivityLog($user, $dates['start_date'], $dates['end_date']);
                break;

            case 'overtime':
                $type = 'overtime';
                $data['report_type'] = 'Overtime Report';
                $report = $this->getOvertimeDataForCurrentMonth($user, $dates['start_date'], $dates['end_date']);
                break;

            default:
        }
        $view = 'report_settings/export/' . $type;

        $pdf = pdh::loadView($view, [
            'orientation' => 'L',
                ],
                [
                    'data' => $data, 
                    'logo' => $this->logo,
                    'user' => $user,
                    'report' => $report,
                    'dates' => $dates,
                    'currency' => 'ر.ع',
                    'lang' => $data->attachment_lang,
                    ]);            
        $data['interval'] = $interval;

        Storage::disk('public')->put($filename, $pdf->output()); 

        Mail::to($user->email)
            ->send(new Reporting($data, $filename, $type));

        return $filename;
    }

    private function getOvertimeDataForCurrentMonth(User $user, $start_date = null, $end_date = null)
    {
        try {
            $currentMonth = date('m');

            $currentYear = date('Y');

            $businessId = $user->business_id;

            // Get all active employees
            $query = User::query();

            if (! empty($locationId)) {
                $query->where('location_id', $locationId);
            } else {
                $query->whereNull('location_id');
            }

            $query = $query->where('business_id', $businessId)
                ->where('status', 'active');

            // $employees = $query->select('id', DB::raw("CONCAT(COALESCE(surname, ''),' ',COALESCE(first_name, ''),' ',COALESCE(last_name,'')) as full_name"))->get();
            $employees = OvertimeSheetController::getActiveEmployeesPerBusiness($businessId);

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
                $totalThirtyMinutes = 0;
                
                foreach ($filteredOvertimeData as $overtimeValue) {
                    if (is_numeric($overtimeValue)) {
                        // Split the value into hours and thirty-minute parts
                        $parts = explode('.', (string)$overtimeValue);
                        $hours = (int)$parts[0];
                        $thirtyMin = isset($parts[1]) && $parts[1] == '5' ? 1 : 0; // .5 means 30 minutes
                        
                        // Add to totals
                        $totalHours += $hours;
                        $totalThirtyMinutes += $thirtyMin;
                    }
                }
                
                // Convert excess 30-minute intervals to hours
                $additionalHours = floor($totalThirtyMinutes / 2);
                $remainingThirtyMin = $totalThirtyMinutes % 2;
                
                // Calculate final total
                $totalOvertimeMonthly = $totalHours + $additionalHours + ($remainingThirtyMin * 0.5);
                
                // Format to ensure consistent decimal format
                $totalOvertimeMonthly = number_format($totalOvertimeMonthly, 1, '.', '');

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

    public function getActivityLog(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $activities = Activity::with(['subject'])
                                ->leftjoin('users as u', 'u.id', '=', 'activity_log.causer_id')
                                ->where('activity_log.business_id', $business_id)
                                ->select(
                                    'activity_log.*',
                                    DB::raw("CONCAT(COALESCE(u.surname, ''), ' ', COALESCE(u.first_name, ''), ' ', COALESCE(u.last_name, '')) as created_by")
                                )->whereDate('activity_log.created_at', '>=', $start_date)
                                ->whereDate('activity_log.created_at', '<=', $end_date)->get();

        return $activities;
    }


    public function getPurchaseSellReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $location_id = 10;

        $purchase_details = $this->transactionUtil->getPurchaseTotals
        (
            $business_id, 
            $start_date,
            $end_date,
            // $location_id
        );

        $sell_details = $this->transactionUtil->getSellTotals(
            $business_id,
            $start_date,
            $end_date,
            // $location_id
        );

        $transaction_types = [
            'purchase_return', 'sell_return',
        ];

        $transaction_totals = $this->transactionUtil->getTransactionTotals(
            $business_id,
            $transaction_types,
            $start_date,
            $end_date,
            $location_id
        );

        $total_purchase_return_inc_tax = $transaction_totals['total_purchase_return_inc_tax'];
        $total_sell_return_inc_tax = $transaction_totals['total_sell_return_inc_tax'];

        $difference = [
            'total' => $sell_details['total_sell_inc_tax'] - $total_sell_return_inc_tax - ($purchase_details['total_purchase_inc_tax'] - $total_purchase_return_inc_tax),
            'due' => $sell_details['invoice_due'] - $purchase_details['purchase_due'],
        ];
        return ['purchase' => $purchase_details,
            'sell' => $sell_details,
            'total_purchase_return' => $total_purchase_return_inc_tax,
            'total_sell_return' => $total_sell_return_inc_tax,
            'difference' => $difference,
        ];
    }

    public function getStockReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;

        $selling_price_groups = SellingPriceGroup::where('business_id', $business_id)->get();
        $allowed_selling_price_group = false;
        foreach ($selling_price_groups as $selling_price_group) {
            if ($user->can('selling_price_group.'.$selling_price_group->id)) {
                $allowed_selling_price_group = true;
                break;
            }
        }
        if ($this->moduleUtil->isModuleInstalled('Manufacturing') && ($user->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'manufacturing_module'))) {
            $show_manufacturing_data = 1;
        } else {
            $show_manufacturing_data = 0;
        }
        $filters = request()->only(['location_id', 'category_id', 'sub_category_id', 'brand_id', 'unit_id', 'tax_id', 'type',
            'only_mfg_products', 'active_state',  'not_for_selling', 'repair_model_id', 'product_id', 'active_state', ]);

        $filters['not_for_selling'] = isset($filters['not_for_selling']) && $filters['not_for_selling'] == 'true' ? 1 : 0;

        $filters['show_manufacturing_data'] = $show_manufacturing_data;


        $products = $this->productUtil->getProductStockDetailsReport($user, $business_id, $filters);

        return $products;
    }

    public function getStockValue(User $user, $start_date = null, $end_date = null, $location_id = null)
    {
        $business_id = $user->business_id;
        $filters = request()->only(['category_id', 'sub_category_id', 'brand_id', 'unit_id']);
        $permitted_locations = $user->permitted_locations();

        //Get Closing stock
        $closing_stock_by_pp = $this->transactionUtil->getOpeningClosingStock(
            $business_id,
            $end_date,
            $location_id,
            false,
            false,
            $filters,
            $permitted_locations
        );

        $closing_stock_by_sp = $this->transactionUtil->getOpeningClosingStock(
            $business_id,
            $end_date,
            $location_id,
            false,
            true,
            $filters,
            $permitted_locations
        );

        $potential_profit = $closing_stock_by_sp - $closing_stock_by_pp;
        $profit_margin = empty($closing_stock_by_sp) ? 0 : ($potential_profit / $closing_stock_by_sp) * 100;
        
        return [
            'closing_stock_by_pp' => $closing_stock_by_pp,
            'closing_stock_by_sp' => $closing_stock_by_sp,
            'potential_profit'    => $potential_profit,
            'profit_margin'       => $profit_margin,
        ];
    }

    public function getProfitOrLossReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $location_id = null;
        
        // $purchase_details = $this->transactionUtil->getPurchaseTotals($business_id, $start_date, $end_date, $location_id);
        $fy = $this->businessUtil->getCurrentFinancialYear($business_id);
        $user_id = $user->id;

        $startDate = $start_date ?? $fy['start'];
        $endDate = $end_date ?? $fy['end'];

        $permitted_locations = $user->permitted_locations();

        $data = $this->transactionUtil->getProfitLossDetails($business_id, $location_id, $startDate, $endDate, $user_id, $permitted_locations);

        Log::info("PROFIT OR LOSS -------------------------------------------------->");
        Log::info(json_encode($data,JSON_PRETTY_PRINT));


        return $data;
    }
    public function getRegisterReport(User $user, $start_date = null, $end_date = null, $location_id = null, $contact_id = null)
    {
        $business_id = $user->business_id;
        $start_date = $start_date;
        $end_date = $end_date;
        $location_id = $location_id;
        $contact_id = $contact_id;

        $permitted_locations = $user->permitted_locations();

        $registers = $this->transactionUtil->registerReportExport($business_id, $permitted_locations, $start_date, $end_date, $user->id);

        return $registers;
    }

    public function getCustomerSupplierReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $contact_id = null;

        $contacts = Contact::where('contacts.business_id', $business_id)
        ->join('transactions AS t', 'contacts.id', '=', 't.contact_id')
        ->active()
        ->groupBy('contacts.id')
        ->select(
            DB::raw("SUM(IF(t.type = 'purchase', final_total, 0)) as total_purchase"),
            DB::raw("SUM(IF(t.type = 'purchase_return', final_total, 0)) as total_purchase_return"),
            DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
            DB::raw("SUM(IF(t.type = 'purchase', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_paid"),
            DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as invoice_received"),
            DB::raw("SUM(IF(t.type = 'sell_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as sell_return_paid"),
            DB::raw("SUM(IF(t.type = 'purchase_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_return_received"),
            DB::raw("SUM(IF(t.type = 'sell_return', final_total, 0)) as total_sell_return"),
            DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
            DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as opening_balance_paid"),
            DB::raw("SUM(IF(t.type = 'ledger_discount' AND sub_type='sell_discount', final_total, 0)) as total_ledger_discount_sell"),
            DB::raw("SUM(IF(t.type = 'ledger_discount' AND sub_type='purchase_discount', final_total, 0)) as total_ledger_discount_purchase"),
            'contacts.supplier_business_name',
            'contacts.name',
            'contacts.id',
            'contacts.type as contact_type'
        );
        $permitted_locations = $user->permitted_locations();

        if ($permitted_locations != 'all') {
            $contacts->whereIn('t.location_id', $permitted_locations);
        }

        if (! empty($start_date) && ! empty($end_date)) {
            $contacts->where('t.transaction_date', '>=', $start_date)
                ->where('t.transaction_date', '<=', $end_date);
        }

        $contacts = $contacts->get();

        return $contacts;
    }

    public function getCustomerGroupReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $contact_id = null;

        $query = Transaction::leftjoin('customer_groups AS CG', 'transactions.customer_group_id', '=', 'CG.id')
        ->where('transactions.business_id', $business_id)
        ->where('transactions.type', 'sell')
        ->where('transactions.status', 'final')
        ->groupBy('transactions.customer_group_id')
        ->select(DB::raw('SUM(final_total) as total_sell'), 'CG.name');

        $group_id = null;

        if (! empty($group_id)) {
            $query->where('transactions.customer_group_id', $group_id);
        }

        $permitted_locations = $user->permitted_locations();

        if ($permitted_locations != 'all') {
            $query->whereIn('transactions.location_id', $permitted_locations);
        }

        if (! empty($start_date) && ! empty($end_date)) {
            $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
        }

        $customer_group = $query->get();

        // Auth::logout();
        // Log::info("CUSTOMER GROUP -------------------------------------------------->");
        // Log::info(json_encode($customer_group,JSON_PRETTY_PRINT));

        return $customer_group;

    }

    // GET Tax Report
    public function getTaxReport(User $user, $start_date = null, $end_date = null, $location_id = null)
    {
        Auth::login($user);

        $business_id = $user->business_id;
        $contact_id = null;

        $input_tax_details = $this->transactionUtil->getInputTax($business_id, $start_date, $end_date, $location_id, $contact_id);

        $output_tax_details = $this->transactionUtil->getOutputTax($business_id, $start_date, $end_date, $location_id, $contact_id);

        $expense_tax_details = $this->transactionUtil->getExpenseTax($business_id, $start_date, $end_date, $location_id, $contact_id);

        $module_output_taxes = $this->moduleUtil->getModuleData('getModuleOutputTax', ['start_date' => $start_date, 'end_date' => $end_date]);

        $total_module_output_tax = 0;

        foreach ($module_output_taxes as $key => $module_output_tax) {
            $total_module_output_tax += $module_output_tax;
        }

        $total_output_tax = $output_tax_details['total_tax'] + $total_module_output_tax;

        $tax_diff = $total_output_tax - $input_tax_details['total_tax'] - $expense_tax_details['total_tax'];

        $taxes = TaxRate::forBusiness($business_id);

        $tax_report_tabs = $this->moduleUtil->getModuleData('getTaxReportViewTabs');

        Auth::logout();

        return [
            'tax_diff' => $tax_diff,
            'taxes' => $taxes,
            'tax_report_tabs' => $tax_report_tabs,
            'input_tax_details' => $input_tax_details,
            'output_tax_details' => $output_tax_details,
            'expense_tax_details' => $expense_tax_details,
        ];
    }

    // GET Stock Adjustment Report
    public function getStockAdjustmentReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $location_id = null;
        $contact_id = null;
        
        $query = Transaction::where('business_id', $business_id)
        ->where('type', 'stock_adjustment');

        //Check for permitted locations of a user
        $permitted_locations = $user->permitted_locations();

        if ($permitted_locations != 'all') {
            $query->whereIn('location_id', $permitted_locations);
        }

        if (! empty($start_date) && ! empty($end_date)) {
            $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
        }

        $datas = $query->get();

        $stock_adjustment_details = $query->select(
            DB::raw('SUM(final_total) as total_amount'),
            DB::raw('SUM(total_amount_recovered) as total_recovered'),
            DB::raw("SUM(IF(adjustment_type = 'normal', final_total, 0)) as total_normal"),
            DB::raw("SUM(IF(adjustment_type = 'abnormal', final_total, 0)) as total_abnormal")
        )->first();

        Log::info("STOCK ADJUSTMENT DETAILS -------------------------------------------------->");
        Log::info(json_encode($stock_adjustment_details,JSON_PRETTY_PRINT));

        return [
            'collection' => $datas,
            'details' => $stock_adjustment_details
        ];
    }

    // GET TRENDING PRODUCT REPORT
    public function getTrendingProducts(User $user, $start_date = null, $end_date = null)
    {                       
        Auth::login($user);
        
        $business_id = $user->business_id;
        $location_id = null;
        $contact_id = null;

        $filters = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'location_id' => $location_id,
            'contact_id' => $contact_id,
        ];

        $products = $this->productUtil->getTrendingProducts($business_id, $filters);
        
        Log::info("TRENDING PRODUCTS -------------------------------------------------->");
        Log::info(json_encode($products,JSON_PRETTY_PRINT));

        Auth::logout();

        return $products;
        
    }

    // GET Items Report
    public function getItemsReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $location_id = null;
        $contact_id = null;

        $query = TransactionSellLinesPurchaseLines::leftJoin('transaction_sell_lines 
            as SL', 'SL.id', '=', 'transaction_sell_lines_purchase_lines.sell_line_id')
        ->leftJoin('stock_adjustment_lines 
            as SAL', 'SAL.id', '=', 'transaction_sell_lines_purchase_lines.stock_adjustment_line_id')
        ->leftJoin('transactions as sale', 'SL.transaction_id', '=', 'sale.id')
        ->leftJoin('transactions as stock_adjustment', 'SAL.transaction_id', '=', 'stock_adjustment.id')
        ->join('purchase_lines as PL', 'PL.id', '=', 'transaction_sell_lines_purchase_lines.purchase_line_id')
        ->join('transactions as purchase', 'PL.transaction_id', '=', 'purchase.id')
        ->join('business_locations as bl', 'purchase.location_id', '=', 'bl.id')
        ->join(
            'variations as v',
            'PL.variation_id',
            '=',
            'v.id'
            )
        ->join('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
        ->join('products as p', 'PL.product_id', '=', 'p.id')
        ->join('units as u', 'p.unit_id', '=', 'u.id')
        ->leftJoin('contacts as suppliers', 'purchase.contact_id', '=', 'suppliers.id')
        ->leftJoin('contacts as customers', 'sale.contact_id', '=', 'customers.id')
        ->where('purchase.business_id', $business_id)
        ->select(
            'v.sub_sku as sku',
            'p.type as product_type',
            'p.name as product_name',
            'v.name as variation_name',
            'pv.name as product_variation',
            'u.short_name as unit',
            'purchase.transaction_date as purchase_date',
            'purchase.ref_no as purchase_ref_no',
            'purchase.type as purchase_type',
            'purchase.id as purchase_id',
            'suppliers.name as supplier',
            'suppliers.supplier_business_name',
            'PL.purchase_price_inc_tax as purchase_price',
            'sale.transaction_date as sell_date',
            'stock_adjustment.transaction_date as stock_adjustment_date',
            'sale.invoice_no as sale_invoice_no',
            'stock_adjustment.ref_no as stock_adjustment_ref_no',
            'customers.name as customer',
            'customers.supplier_business_name as customer_business_name',
            'transaction_sell_lines_purchase_lines.quantity as quantity',
            'SL.unit_price_inc_tax as selling_price',
            'SAL.unit_price as stock_adjustment_price',
            'transaction_sell_lines_purchase_lines.stock_adjustment_line_id',
            'transaction_sell_lines_purchase_lines.sell_line_id',
            'transaction_sell_lines_purchase_lines.purchase_line_id',
            'transaction_sell_lines_purchase_lines.qty_returned',
            'bl.name as location',
            'SL.sell_line_note',
            'PL.lot_number'
        );

        $permitted_locations = $user->permitted_locations();

        if ($permitted_locations != 'all') {
            $query->whereIn('purchase.location_id', $permitted_locations);
        }
        
        $items_report = $query->get();

        // Log::info("ITEMS REPORT DATA-------------------------------------------------->");
        // Log::info(json_encode($items_report,JSON_PRETTY_PRINT));

        return $items_report;
            
    }

    public function getProductPurchaseReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $location_id = null;
        $contact_id = null;
        $variation_id = null;

        $query = PurchaseLine::join(
            'transactions as t',
            'purchase_lines.transaction_id',
            '=',
            't.id'
                )
                ->join(
                    'variations as v',
                    'purchase_lines.variation_id',
                    '=',
                    'v.id'
                )
                ->join('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
                ->join('contacts as c', 't.contact_id', '=', 'c.id')
                ->join('products as p', 'pv.product_id', '=', 'p.id')
                ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
                ->where('t.business_id', $business_id)
                ->where('t.type', 'purchase')
                ->select(
                    'p.name as product_name',
                    'p.type as product_type',
                    'pv.name as product_variation',
                    'v.name as variation_name',
                    'v.sub_sku',
                    'c.name as supplier',
                    'c.supplier_business_name',
                    't.id as transaction_id',
                    't.ref_no',
                    't.transaction_date as transaction_date',
                    'purchase_lines.purchase_price_inc_tax as unit_purchase_price',
                    DB::raw('(purchase_lines.quantity - purchase_lines.quantity_returned) as purchase_qty'),
                    'purchase_lines.quantity_adjusted',
                    'u.short_name as unit',
                    DB::raw('((purchase_lines.quantity - purchase_lines.quantity_returned - purchase_lines.quantity_adjusted) * purchase_lines.purchase_price_inc_tax) as subtotal')
                )
                ->groupBy('purchase_lines.id');

        
        if (! empty($start_date) && ! empty($end_date)) {
            $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
        }

        $permitted_locations = $user->permitted_locations();

        if ($permitted_locations != 'all') {
            $query->whereIn('t.location_id', $permitted_locations);
        }

        $product_purchase_report = $query->get();

        Log::info("PRODUCT PURCHASE REPORT -------------------------------------------------->");
        Log::info(json_encode($product_purchase_report,JSON_PRETTY_PRINT));

        return $product_purchase_report;
    
    }


    public function getProductSellReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $location_id = null;
        $contact_id = null;
        $variation_id = null;
        $custom_labels = json_decode(session('business.custom_labels'), true);
        $product_custom_field1 = !empty($custom_labels['product']['custom_field_1']) ? $custom_labels['product']['custom_field_1'] : '';
        $product_custom_field2 = !empty($custom_labels['product']['custom_field_2']) ? $custom_labels['product']['custom_field_2'] : '';


        $payment_types = $this->transactionUtil->payment_types(null, true, $business_id);

        $query = TransactionSellLine::join(
            'transactions as t',
            'transaction_sell_lines.transaction_id',
            '=',
            't.id'
        )
            ->join(
                'variations as v',
                'transaction_sell_lines.variation_id',
                '=',
                'v.id'
            )
            ->join('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
            ->join('contacts as c', 't.contact_id', '=', 'c.id')
            ->join('products as p', 'pv.product_id', '=', 'p.id')
            ->leftjoin('tax_rates', 'transaction_sell_lines.tax_id', '=', 'tax_rates.id')
            ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
            ->where('t.business_id', $business_id)
            ->where('t.type', 'sell')
            ->where('t.status', 'final')
            ->with('transaction.payment_lines')
            ->select(
                'p.name as product_name',
                'p.type as product_type',
                'p.product_custom_field1 as product_custom_field1',
                'p.product_custom_field2 as product_custom_field2',
                'pv.name as product_variation',
                'v.name as variation_name',
                'v.sub_sku',
                'c.name as customer',
                'c.supplier_business_name',
                'c.contact_id',
                't.id as transaction_id',
                't.invoice_no',
                't.transaction_date as transaction_date',
                'transaction_sell_lines.unit_price_before_discount as unit_price',
                'transaction_sell_lines.unit_price_inc_tax as unit_sale_price',
                DB::raw('(transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) as sell_qty'),
                'transaction_sell_lines.line_discount_type as discount_type',
                'transaction_sell_lines.line_discount_amount as discount_amount',
                'transaction_sell_lines.item_tax',
                'tax_rates.name as tax',
                'u.short_name as unit',
                'transaction_sell_lines.parent_sell_line_id',
                DB::raw('((transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) * transaction_sell_lines.unit_price_inc_tax) as subtotal')
            )
            ->groupBy('transaction_sell_lines.id');        

        if (! empty($start_date) && ! empty($end_date)) {
            $query->where('t.transaction_date', '>=', $start_date)
                ->where('t.transaction_date', '<=', $end_date);
        }

        $product_sell_report = $query->get();

        Log::info("PRODUCT SELL REPORT -------------------------------------------------->");
        Log::info(json_encode($product_sell_report,JSON_PRETTY_PRINT));

        return $product_sell_report;

    }

    public function getPurchasePaymentReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $location_id = null;
        $contact_id = null;
        $supplier_id = null;
        $contact_filter1 = ! empty($supplier_id) ? "AND t.contact_id=$supplier_id" : '';
        $contact_filter2 = ! empty($supplier_id) ? "AND transactions.contact_id=$supplier_id" : '';

        $parent_payment_query_part = empty($location_id) ? 'AND transaction_payments.parent_id IS NULL' : '';

        $query = TransactionPayment::leftjoin('transactions as t', function ($join) use ($business_id) {
            $join->on('transaction_payments.transaction_id', '=', 't.id')
                ->where('t.business_id', $business_id)
                ->whereIn('t.type', ['purchase', 'opening_balance']);
        })
            ->where('transaction_payments.business_id', $business_id)
            ->where(function ($q) use ($business_id, $contact_filter1, $contact_filter2, $parent_payment_query_part) {
                $q->whereRaw("(transaction_payments.transaction_id IS NOT NULL AND t.type IN ('purchase', 'opening_balance')  $parent_payment_query_part $contact_filter1)")
                    ->orWhereRaw("EXISTS(SELECT * FROM transaction_payments as tp JOIN transactions ON tp.transaction_id = transactions.id WHERE transactions.type IN ('purchase', 'opening_balance') AND transactions.business_id = $business_id AND tp.parent_id=transaction_payments.id $contact_filter2)");
            })

            ->select(
                DB::raw("IF(transaction_payments.transaction_id IS NULL, 
                            (SELECT c.name FROM transactions as ts
                            JOIN contacts as c ON ts.contact_id=c.id 
                            WHERE ts.id=(
                                    SELECT tps.transaction_id FROM transaction_payments as tps
                                    WHERE tps.parent_id=transaction_payments.id LIMIT 1
                                )
                            ),
                            (SELECT CONCAT(COALESCE(c.supplier_business_name, ''), '<br>', c.name) FROM transactions as ts JOIN
                                contacts as c ON ts.contact_id=c.id
                                WHERE ts.id=t.id 
                            )
                        ) as supplier"),
                'transaction_payments.amount',
                'method',
                'paid_on',
                'transaction_payments.payment_ref_no',
                'transaction_payments.document',
                't.ref_no',
                't.id as transaction_id',
                'cheque_number',
                'card_transaction_number',
                'bank_account_number',
                'transaction_no',
                'transaction_payments.id as DT_RowId'
            )
            ->groupBy('transaction_payments.id');


            if (! empty($start_date) && ! empty($end_date)) {
                $query->whereBetween(DB::raw('date(paid_on)'), [$start_date, $end_date]);
            }

        $permitted_locations = $user->permitted_locations();

        if ($permitted_locations != 'all') {
            $query->whereIn('t.location_id', $permitted_locations);
        }
        $payment_types = $this->transactionUtil->payment_types(null, true, $business_id);


        $purchase_payment_report = $query->get();

        Log::info("PURCHASE PAYMENT REPORT -------------------------------------------------->");
        Log::info(json_encode($purchase_payment_report,JSON_PRETTY_PRINT));

        return $purchase_payment_report;
        

        
    }

    public function getSalesRepresentativeTotalSell(User $user, $start_date = null, $end_date = null, $location_id = null, $contact_id = null)
    {
        $business_id = $user->business_id;

        $start_date = $start_date;
        $end_date = $end_date;

        $location_id = $location_id;
        $created_by = null;

        $sell_details = $this->transactionUtil->getSellTotals($business_id, $start_date, $end_date, $location_id, $created_by);

        //Get Sell Return details
        $transaction_types = [
            'sell_return',
        ];
        $sell_return_details = $this->transactionUtil->getTransactionTotals(
            $business_id,
            $transaction_types,
            $start_date,
            $end_date,
            $location_id,
            $created_by
        );

        $total_sell_return = ! empty($sell_return_details['total_sell_return_exc_tax']) ? $sell_return_details['total_sell_return_exc_tax'] : 0;
        $total_sell = $sell_details['total_sell_exc_tax'] - $total_sell_return;

        return [
            'total_sell_exc_tax' => $sell_details['total_sell_exc_tax'],
            'total_sell_return_exc_tax' => $total_sell_return,
            'total_sell' => $total_sell,
        ];
    }

    public function getSalesRepresentativeTotalExpense(User $user, $start_date = null, $end_date = null, $location_id = null, $contact_id = null)
    {
        $business_id = $user->business_id;

        $filters = [
            'location_id' => $location_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];

        $total_expense = $this->transactionUtil->getExpenseReport($business_id, $filters, 'total');

        return $total_expense;
    }

    public function getSalesRepresentativeTotalCommission(User $user, $start_date = null, $end_date = null, $location_id = null, $contact_id = null)
    {
        $business_id = $user->business_id;

        $start_date = $start_date;
        $end_date = $end_date;

        $location_id = $location_id;
        $commission_agent = null;

        $business_details = $this->businessUtil->getDetails($business_id);
        $pos_settings = empty($business_details->pos_settings) ? $this->businessUtil->defaultPosSettings() : json_decode($business_details->pos_settings, true);

        $commsn_calculation_type = empty($pos_settings['cmmsn_calculation_type']) || $pos_settings['cmmsn_calculation_type'] == 'invoice_value' ? 'invoice_value' : $pos_settings['cmmsn_calculation_type'];

        $commission_percentage = $user->cmmsn_percent;

        if ($commsn_calculation_type == 'payment_received') {
            $payment_details = $this->transactionUtil->getTotalPaymentWithCommissionExport($user, $business_id, $start_date, $end_date, $location_id, $commission_agent);

            //Get Commision
            $total_commission = $commission_percentage * $payment_details['total_payment_with_commission'] / 100;

            return ['total_payment_with_commission' => $payment_details['total_payment_with_commission'] ?? 0,
                'total_commission' => $total_commission,
                'commission_percentage' => $commission_percentage,
            ];
        }

        $sell_details = $this->transactionUtil->getTotalSellCommissionExport($user, $business_id, $start_date, $end_date, $location_id, $commission_agent);

        //Get Commision
        $total_commission = $commission_percentage * $sell_details['total_sales_with_commission'] / 100;

        return ['total_sales_with_commission' => $sell_details['total_sales_with_commission'],
            'total_commission' => $total_commission,
            'commission_percentage' => $commission_percentage,
        ];
    }

    public function getSalesRepresentativeExpenseCollection(User $user, $start_date = null, $end_date = null, $location_id = null, $contact_id = null)
    {
        $business_id = $user->business_id;

        $expenses = Transaction::leftJoin('expense_categories AS ec', 'transactions.expense_category_id', '=', 'ec.id')
                    ->leftJoin('expense_categories AS esc', 'transactions.expense_sub_category_id', '=', 'esc.id')
                    ->join(
                        'business_locations AS bl',
                        'transactions.location_id',
                        '=',
                        'bl.id'
                    )
                    ->leftJoin('tax_rates as tr', 'transactions.tax_id', '=', 'tr.id')
                    ->leftJoin('users AS U', 'transactions.expense_for', '=', 'U.id')
                    ->leftJoin('users AS usr', 'transactions.created_by', '=', 'usr.id')
                    ->leftJoin('contacts AS c', 'transactions.contact_id', '=', 'c.id')
                    ->leftJoin(
                        'transaction_payments AS TP',
                        'transactions.id',
                        '=',
                        'TP.transaction_id'
                    )
                    ->where('transactions.business_id', $business_id)
                    ->whereIn('transactions.type', ['expense', 'expense_refund'])
                    ->select(
                        'transactions.id',
                        'transactions.document',
                        'transaction_date',
                        'ref_no',
                        'ec.name as category',
                        'esc.name as sub_category',
                        'payment_status',
                        'additional_notes',
                        'final_total',
                        'transactions.is_recurring',
                        'transactions.recur_interval',
                        'transactions.recur_interval_type',
                        'transactions.recur_repetitions',
                        'transactions.subscription_repeat_on',
                        'bl.name as location_name',
                        DB::raw("CONCAT(COALESCE(U.surname, ''),' ',COALESCE(U.first_name, ''),' ',COALESCE(U.last_name,'')) as expense_for"),
                        DB::raw("CONCAT(tr.name ,' (', tr.amount ,' )') as tax"),
                        DB::raw('SUM(TP.amount) as amount_paid'),
                        DB::raw("CONCAT(COALESCE(usr.surname, ''),' ',COALESCE(usr.first_name, ''),' ',COALESCE(usr.last_name,'')) as added_by"),
                        'transactions.recur_parent_id',
                        'c.name as contact_name',
                        'transactions.type'
                    )
                    ->with(['recurring_parent'])
                    ->groupBy('transactions.id');

        $expenses->whereDate('transaction_date', '>=', $start_date)
                ->whereDate('transaction_date', '<=', $end_date)
                ->get();

        return $expenses;
    }

    public function getSalesRepresentativeSalesCollection(User $user, $start_date = null, $end_date = null, $location_id = null, $contact_id = null)
    {
        $business_id = $user->business_id;
        $sells = $this->transactionUtil->getListSells($business_id, null);

        $permitted_locations = $user->permitted_locations();
            if ($permitted_locations != 'all') {
                $sells->whereIn('transactions.location_id', $permitted_locations);
            }

        $sells->whereDate('transactions.transaction_date', '>=', $start_date)
                ->whereDate('transactions.transaction_date', '<=', $end_date);

        return $sells;
    }
    
    public function getExpenseReport(User $user, $start_date = null, $end_date = null, $location_id = null, $contact_id = null)
    {
        $business_id = $user->business_id;

        $start_date = $start_date;
        $end_date = $end_date;

        $filters = [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
        $expenses = $this->transactionUtil->getExpenseReport($business_id, $filters);

        return $expenses;
    }
}