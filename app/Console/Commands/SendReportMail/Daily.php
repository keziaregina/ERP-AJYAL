<?php

namespace App\Console\Commands\SendReportMail;

use App\User;
use App\Mail\Reporting;
use App\ReportSettings;
use App\Mail\Reporting2;
use App\Transaction;
use App\Utils\TransactionUtil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Utils\ProductUtil;
use App\Utils\BusinessUtil;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class Daily extends Command
{
    public $transactionUtil;
    public $productUtil;
    public $filename;
    public $businessUtil;
    
    public $logo;

    public function __construct(TransactionUtil $transactionUtil, ProductUtil $productUtil, BusinessUtil $businessUtil)
    {
        parent::__construct();
        $this->transactionUtil = $transactionUtil;
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
    protected $signature = 'send-report:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily report emails based on the report type selected by the user.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $datas = ReportSettings::where('interval', 'daily')->get();

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
                    $data['report_type'] = 'Tax';
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
                case 'profit_or_loss_report':
                    $type = 'profit_or_loss';
                    $data['report_type'] = 'Profit / Loss';
                    $report = $this->getProfitOrLossReport($user, $dates['start_date'], $dates['end_date']);
                    break;
                case 'activity_log':
                    $type = 'activity_log';
                    $data['report_type'] = 'Activity Log';
                    $report = $this->getActivityLog($user, $dates['start_date'], $dates['end_date']);
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

            $data['interval'] = 'daily';


            $file=Storage::disk('public')->put($filename, $pdf->output()); 

            Mail::to($user->email)
                ->send(new Reporting($data, $filename, $type));

            Storage::disk('public')->delete($filename);
        }
    }

    public function getDay()
    {
        $start_date = now()->subDay()->toDateString();
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

        
        // $for = request()->input('for') == 'view_product' ? 'view_product' : 'datatables';

        // $products = $this->productUtil->getProductStockDetails($business_id, $filters, $for);
        // // \Log::info($products);

        return [
            'closing_stock_by_pp' => $closing_stock_by_pp,
            'closing_stock_by_sp' => $closing_stock_by_sp,
            'potential_profit' => $potential_profit,
            'profit_margin' => $profit_margin,
        ];
    }

    public function getProfitOrLossReport(User $user, $start_date = null, $end_date = null)
    {
        $business_id = $user->business_id;
        $location_id = 10;
        $purchase_details = $this->transactionUtil->getPurchaseTotals($business_id, $start_date, $end_date, $location_id);
        $fy = $this->businessUtil->getCurrentFinancialYear($business_id);
        $user_id = $user->id;
        $permitted_locations = $user->permitted_locations();
        $data = $this->transactionUtil->getProfitLossDetails($business_id, $location_id, $start_date, $end_date, $user_id, $permitted_locations);
        \Log::info($data);
        return $data;
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

        if (! empty(request()->user_id)) {
            $activities->where('causer_id', request()->user_id);
        }

        $subject_type = request()->subject_type;
        if (! empty($subject_type)) {
            if ($subject_type == 'contact') {
                $activities->where('subject_type', \App\Contact::class);
            } elseif ($subject_type == 'user') {
                $activities->where('subject_type', \App\User::class);
            } elseif (in_array($subject_type, ['sell', 'purchase',
                'sales_order', 'purchase_order', 'sell_return', 'purchase_return', 'sell_transfer', 'expense', 'purchase_order', ])) {
                $activities->where('subject_type', \App\Transaction::class);
                $activities->whereHasMorph('subject', Transaction::class, function ($q) use ($subject_type) {
                    $q->where('type', $subject_type);
                });
            }
        }

        $sell_statuses = Transaction::sell_statuses();
        $sales_order_statuses = Transaction::sales_order_statuses(true);
        $purchase_statuses = $this->transactionUtil->orderStatuses();
        $shipping_statuses = $this->transactionUtil->shipping_statuses();

        $statuses = array_merge($sell_statuses, $sales_order_statuses, $purchase_statuses);
        
        $activities = $activities->map(function ($row) use ($statuses, $shipping_statuses) {
            $html = '';

            $subject_type = '';
            if ($row->subject_type == \App\Contact::class) {
                $subject_type = __('contact.contact');
            } elseif ($row->subject_type == \App\User::class) {
                $subject_type = __('report.user');
            } elseif ($row->subject_type == \App\Transaction::class && ! empty($row->subject->type)) {
                $subject_type = isset($transaction_types[$row->subject->type]) ? $transaction_types[$row->subject->type] : '';
            } elseif (($row->subject_type == \App\TransactionPayment::class)) {
                $subject_type = __('lang_v1.payment');
            }
    
            if (!empty($row->subject?->ref_no)) {
                $html .= __('purchase.ref_no') . ': ' . $row->subject->ref_no . '<br>';
            }
    
            if (!empty($row->subject?->invoice_no)) {
                $html .= __('sale.invoice_no') . ': ' . $row->subject->invoice_no . '<br>';
            }
    
            if ($row->subject_type === \App\Models\Transaction::class && in_array($row->subject?->type, ['sell', 'purchase'])) {
                $html .= view('sale_pos.partials.activity_row', [
                    'activity' => $row,
                    'statuses' => $statuses,
                    'shipping_statuses' => $shipping_statuses
                ])->render();
            } else {
                $update_note = $row->getExtraProperty('update_note');
                if (!empty($update_note) && !is_array($update_note)) {
                    $html .= $update_note;
                }
            }
    
            if ($row->description === 'contact_deleted') {
                $html .= $row->getExtraProperty('supplier_business_name') ?? '';
                $html .= '<br>';
            }
    
            if (!empty($row->getExtraProperty('name'))) {
                $html .= __('user.name') . ': ' . $row->getExtraProperty('name') . '<br>';
            }
    
            if (!empty($row->getExtraProperty('id'))) {
                $html .= 'ID: ' . $row->getExtraProperty('id') . '<br>';
            }
    
            if (!empty($row->getExtraProperty('invoice_no'))) {
                $html .= __('sale.invoice_no') . ': ' . $row->getExtraProperty('invoice_no') . '<br>';
            }
    
            if (!empty($row->getExtraProperty('ref_no'))) {
                $html .= __('purchase.ref_no') . ': ' . $row->getExtraProperty('ref_no');
            }

            $row['note'] = $html; 
            $row['subject_type'] = $subject_type;
            return $row;
        });
        return $activities;
    }


}
