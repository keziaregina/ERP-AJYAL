<?php

namespace App\Console\Commands\SendReportMail;

use App\Mail\Reporting;
use App\ReportSettings;
use App\SellingPriceGroup;
use App\Services\ReportEmailService;
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
    public $reportEmailService;

    public $filename;
    
    public $logo;

    public function __construct(TransactionUtil $transactionUtil, ModuleUtil $moduleUtil, ProductUtil $productUtil, BusinessUtil $businessUtil, ReportEmailService $reportEmailService)
    {
        parent::__construct();
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->productUtil = $productUtil;
        $this->businessUtil = $businessUtil;
        $this->logo = public_path('img/logo-small.png');
        $this->reportEmailService = $reportEmailService;
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
            $this->reportEmailService->generateReportAttachment($data, $this->getDay(), $data->interval);
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
}