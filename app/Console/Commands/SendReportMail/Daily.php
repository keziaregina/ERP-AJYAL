<?php

namespace App\Console\Commands\SendReportMail;

use App\Mail\Reporting;
use App\Mail\Reporting2;
use App\ReportSettings;
use App\User;
use App\Utils\TransactionUtil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Daily extends Command
{
    public $transactionUtil;

    public $filename;
    
    public $logo;

    public function __construct(TransactionUtil $transactionUtil)
    {
        parent::__construct();
        $this->transactionUtil = $transactionUtil;
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
                    $report = $this->getPurchaseSellReport($user, $dates['start_date'], $dates['end_date']);
                    break;
                case 'contacts_report':
                    $type = 'contact';
                    break;
                case 'tax_report':
                    $type = 'tax';
                    break;
                case 'stock_report':
                    $type = 'stock';
                    $report = $this->getStockValue($user, $dates['start_date'], $dates['end_date']);
                    break;
                case 'trending_product_report':
                    $type = 'trending_product';
                    break;
                case 'sales_representative':
                    $type = 'sales_representative';
                    break;
                case 'register_report':
                    $type = 'register';
                    break;
                case 'expense_report':
                    $type = 'expense';
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
            $file=Storage::disk('public')->put($filename, $pdf->output()); 
            Mail::to($user->email)
                ->queue(new Reporting($data, $filename));
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

        return [
            'closing_stock_by_pp' => $closing_stock_by_pp,
            'closing_stock_by_sp' => $closing_stock_by_sp,
            'potential_profit' => $potential_profit,
            'profit_margin' => $profit_margin,
        ];
    }
}
