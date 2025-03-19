<?php

namespace App\Console\Commands\SendReportMail;

use App\Mail\Reporting;
use App\ReportSettings;
use App\SellingPriceGroup;
use App\TaxRate;
use App\Transaction;
use App\User;
use App\Utils\BusinessUtil;
use App\Utils\ModuleUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class Weekly extends Command
{
    public $transactionUtil;
    public $productUtil;
    public $businessUtil;
    public $moduleUtil;

    public $filename;
    
    public $logo;

    public function __construct(TransactionUtil $transactionUtil, ModuleUtil $moduleUtil, ProductUtil $productUtil, BusinessUtil $businessUtil)
    {
        parent::__construct();
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->productUtil = $productUtil;
        $this->businessUtil = $businessUtil;
        $this->logo = public_path('img/logo-small.png');
        // $this->filename = storage_path('app/public/pdf/report/Ajyal Al-Madina.pdf');
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-report:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly report emails based on the report type selected by the user.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $datas = ReportSettings::where('interval', 'weekly')->get();

        foreach ($datas as $data) {
            $report = null;
            
            $filename = "pdf/report/{$data->user_id}_{$data->type}_" . now()->format('Ymd_His') . ".pdf";
            $user = User::find($data->user_id);
            $location_id = $user->location_id;

            $directory = dirname($filename);

            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $dates = $this->getDay();

            switch ($data->type) {
                case 'purchase_n_sell_report':
                    $type = 'purchase_n_sale';
                    $data['report_type'] = 'Purchase & Sales Summary';
                    $report = $this->getPurchaseSellReport($user, $dates['start_date'], $dates['end_date']);
                    break;
                case 'tax_report':
                    $type = 'tax';
                    $data['report_type'] = 'Taxes Summary';
                    $report = $this->getTaxReport($user, $dates['start_date'], $dates['end_date']);
                    break;
                case 'stock_report':
                    $type = 'stock';
                    $data['report_type'] = 'Stocks Summary';
                    $report = [
                        'stock_report' => $this->getStockReport($user, $dates['start_date'], $dates['end_date']),
                        'stock_value' => $this->getStockValue($user, $dates['start_date'], $dates['end_date']),
                    ];
                    break;
                case 'trending_product_report':
                    $type = 'trending_product';
                    $data['report_type'] = 'Trending Products Summary';
                    break;
                case 'sales_representative':
                    $type = 'sales_representative';
                    $data['report_type'] = 'Sales Representative Summary';
                    $report = [
                        'overall' => [
                            'sell' => $this->getSalesRepresentativeTotalSell($user, $dates['start_date'], $dates['end_date'], $location_id),
                            'expense' => $this->getSalesRepresentativeTotalExpense($user, $dates['start_date'], $dates['end_date'], $location_id)
                        ],
                        'collection' => [
                            'expense' => $this->getSalesRepresentativeExpenseCollection($user, $dates['start_date'], $dates['end_date'], $location_id)->get(),
                            'sales' => $this->getSalesRepresentativeSalesCollection($user, $dates['start_date'], $dates['end_date'], $location_id)->get(),
                        ]
                    ];
                    break;
                case 'register_report':
                    $type = 'register';
                    $data['report_type'] = 'Registers Summary';
                    $report = $this->getRegisterReport($user, $dates['start_date'], $dates['end_date'])->get();
                break;
                case 'expense_report':
                    $type = 'expense';
                    $data['report_type'] = 'Expenses Summary';
                    $report = $this->getExpenseReport($user, $dates['start_date'], $dates['end_date']);
                break;
                default:
            }
            $view = 'report_settings/export/' . $type;
        
            $pdf = Pdf::setPaper('a4', 'landscape')
                ->loadView($view, [
                    'data' => $data, 
                    'logo' => $this->logo, 
                    'user' => $user,
                    'report' => $report,
                    'dates' => $dates,
                    'currency' => 'ر.س'
                ]);
            // $pdf->save(Storage::disk('public')->path($filename));
            Storage::disk('public')->put($filename, $pdf->output()); 
            // Mail::to($user->email)
            //     ->queue(new Reporting($data, $filename, $type));
        }
    }

    public function getDay()
    {
        $start_date = now()->subDays(7)->toDateString();
        $end_date = now()->toDateString();

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
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
        $location_id = $location_id;
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
            'potential_profit' => $potential_profit,
            'profit_margin' => $profit_margin,
        ];
    }

    public function getTaxReport(User $user, $start_date = null, $end_date = null, $location_id = null, $contact_id = null)
    {
        $business_id = $user->business_id;
        $start_date = $start_date;
        $end_date = $end_date;
        $location_id = $location_id;
        $contact_id = $contact_id;

        $input_tax_details = $this->transactionUtil->getInputTaxReport($user, $business_id, $start_date, $end_date, $location_id, $contact_id);

        $output_tax_details = $this->transactionUtil->getOutputTaxReport($user, $business_id, $start_date, $end_date, $location_id, $contact_id);

        $expense_tax_details = $this->transactionUtil->getExpenseTaxReport($user, $business_id, $start_date, $end_date, $location_id, $contact_id);

        $module_output_taxes = $this->moduleUtil->getModuleData('getModuleOutputTax', ['start_date' => $start_date, 'end_date' => $end_date]);

        $total_module_output_tax = 0;
        foreach ($module_output_taxes as $key => $module_output_tax) {
            $total_module_output_tax += $module_output_tax;
        }

        $total_output_tax = $output_tax_details['total_tax'] + $total_module_output_tax;

        $tax_diff = $total_output_tax - $input_tax_details['total_tax'] - $expense_tax_details['total_tax'];

        $taxes = TaxRate::forBusiness($business_id);

        return [
            'tax_diff' => $tax_diff,
            'taxes' => $taxes,
            'input_tax_details' => $input_tax_details,
            'output_tax_details' => $output_tax_details,
            'expense_tax_details' => $expense_tax_details,
        ];
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

    public function getSalesRepresentativeReport(User $user, $start_date = null, $end_date = null, $location_id = null, $contact_id = null)
    {
        $business_id = $user->business_id;

        $users = User::allUsersDropdown($business_id, false);
        // $business_locations = BusinessLocation::forDropdown($business_id, true);

        $business_details = $this->businessUtil->getDetails($business_id);
        $pos_settings = empty($business_details->pos_settings) ? $this->businessUtil->defaultPosSettings() : json_decode($business_details->pos_settings, true);
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