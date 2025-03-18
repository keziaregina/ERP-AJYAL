<?php

namespace App\Console\Commands\SendReportMail;

use App\User;
use App\Contact;
use App\TaxRate;
use App\Mail\Reporting;
use App\ReportSettings;
use App\Mail\Reporting2;
use App\Utils\ModuleUtil;
use App\Utils\TransactionUtil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Monthly extends Command
{
    public $transactionUtil;    
    public $filename;
    public $logo;
    public $moduleUtil;

    public function __construct(TransactionUtil $transactionUtil, ModuleUtil $moduleUtil)
    {
        parent::__construct();
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->logo = public_path('img/logo-small.png');
        // $this->filename = storage_path('app/public/pdf/report/Ajyal Al-Madina.pdf');
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-report:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send monthly report emails based on the report type selected by the user.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $datas = ReportSettings::where('interval', 'monthly')->get();

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
                    // TODO: fix tax data
                case 'tax_report':
                    $type = 'tax';
                    $data['report_type'] = 'Tax';
                    $report = $this->getTaxReport($user, $dates['start_date'], $dates['end_date']);
                    break;
                    
                case 'customer_n_supplier_report':
                    Log::info("CUSTOMER & SUPPLIER -------------------------------------------------->");
                    $type = 'customer_n_supplier';
                    $data['report_type'] = 'Customer & Supplier';
                    $report = $this->getCustomerSupplierReport($user, $dates['start_date'], $dates['end_date']);
                    break;
                    
                case 'stock_report':
                    $type = 'stock';
                    $data['report_type'] = 'Stock';
                    $report = $this->getStockValue($user, $dates['start_date'], $dates['end_date']);
                    break;

                case 'trending_product_report':
                    $type = 'trending_product';
                    $data['report_type'] = 'Trending Product';
                    break;
                case 'sales_representative':
                    $type = 'sales_representative';
                    break;
                case 'register_report':
                    $type = 'register';
                    $data['report_type'] = 'Register';
                    break;
                case 'expense_report':
                    $type = 'expense';
                    $data['report_type'] = 'Expense';
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

            $data['interval'] = 'monthly';

            $file = Storage::disk('public')->put($filename, $pdf->output()); 
            
            Mail::to($user->email)
                ->queue(new Reporting($data, $filename));


        }

    }

    public function getDay()
    {
        $start_date = now()->startOfYear()->toDateString();
        $end_date = now()->endOfYear()->toDateString();
        // $start_date = now()->subMonth()->startOfMonth()->toDateString();
        // $end_date = now()->subMonth()->endOfMonth()->toDateString();

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
    }

    public function getPurchaseSellReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $location_id = 10;

        $purchase_details = $this->transactionUtil->getPurchaseTotals(
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

    public function getTaxReport(User $user, $start_date = null, $end_date = null, $location_id = null)
    {
        // Login as the user
        Auth::login($user);

        $business_id = $user->business_id;
        // $location_id = $location_id;
        // $contact_id = $request->get('contact_id');
        // $contact_id = 0;
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


        return [
            'tax_diff' => $tax_diff,
            'taxes' => $taxes,
            'tax_report_tabs' => $tax_report_tabs,
        ];

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

    public function getCustomerSupplierReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        // $location_id = 10;
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
        $permitted_locations = auth()->user()->permitted_locations();

        if ($permitted_locations != 'all') {
            $contacts->whereIn('t.location_id', $permitted_locations);
        }

        // if (! empty($request->input('customer_group_id'))) {
        //     $contacts->where('contacts.customer_group_id', $request->input('customer_group_id'));
        // }

        // if (! empty($request->input('location_id'))) {
        //     $contacts->where('t.location_id', $request->input('location_id'));
        // }

        // if (! empty($request->input('contact_id'))) {
        //     $contacts->where('t.contact_id', $request->input('contact_id'));
        // }

        // if (! empty($request->input('contact_type'))) {
        //     $contacts->whereIn('contacts.type', [$request->input('contact_type'), 'both']);
        // }

        // $start_date = $request->get('start_date');
        // $end_date = $request->get('end_date');
        if (! empty($start_date) && ! empty($end_date)) {
            $contacts->where('t.transaction_date', '>=', $start_date)
                ->where('t.transaction_date', '<=', $end_date);
        }



        $contacts = $contacts->get();

        foreach ($contacts as $row) {
            if (! empty($row->supplier_business_name)) {
                $row->name .= ', '.$row->supplier_business_name;
            }
            
            $total_ledger_discount_purchase = $row->total_ledger_discount_purchase ?? 0;
            $total_ledger_discount_sell = $total_ledger_discount_sell ?? 0;
            $due = ($row->total_invoice - $row->invoice_received - $total_ledger_discount_sell) - ($row->total_purchase - $row->purchase_paid - $total_ledger_discount_purchase) - ($row->total_sell_return - $row->sell_return_paid) + ($row->total_purchase_return - $row->purchase_return_received);

            if ($row->contact_type == 'supplier') {
                $due -= $row->opening_balance - $row->opening_balance_paid;
            } else {
                $due += $row->opening_balance - $row->opening_balance_paid;
            }

            $due_formatted = $this->transactionUtil->num_f($due, true);
        
            $row->due = $due_formatted;
        }
        Log::info("CONTACT -------------------------------------------------->");
        Log::info(json_encode($contacts,JSON_PRETTY_PRINT));

        return $contacts;
    }
}
