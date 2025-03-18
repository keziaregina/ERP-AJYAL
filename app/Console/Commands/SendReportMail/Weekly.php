<?php

namespace App\Console\Commands\SendReportMail;

use App\Mail\Reporting;
use App\ReportSettings;
use App\SellingPriceGroup;
use App\TaxRate;
use App\User;
use App\Utils\ModuleUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Weekly extends Command
{
    public $transactionUtil;
    public $productUtil;
    public $moduleUtil;

    public $filename;
    
    public $logo;

    public function __construct(TransactionUtil $transactionUtil, ModuleUtil $moduleUtil, ProductUtil $productUtil)
    {
        parent::__construct();
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->productUtil = $productUtil;
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
                case 'contacts_report':
                    $type = 'contact';
                    $data['report_type'] = 'Contacts Summary';
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
                    break;
                case 'register_report':
                    $type = 'register';
                    $data['report_type'] = 'Registers Summary';
                    $report = $this->getRegisterReport($user, $dates['start_date'], $dates['end_date']);
                    break;
                case 'expense_report':
                    $type = 'expense';
                    $data['report_type'] = 'Expenses Summary';
                    break;
                default:
            }
            $view = 'report_settings/export/' . $type;
            $view = 'report_settings/export/stock';

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
            Mail::to($user->email)
                ->queue(new Reporting($data, $filename));
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
}
