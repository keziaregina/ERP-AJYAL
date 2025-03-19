<?php

namespace App\Console\Commands\SendReportMail;

use App\User;
use App\Mail\Reporting;
use App\ReportSettings;
use App\Mail\Reporting2;
use App\Services\ReportEmailService;
use App\Services\ReportService;
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
    public $reportEmailService;
    public $logo;

    public function __construct(TransactionUtil $transactionUtil, ProductUtil $productUtil, BusinessUtil $businessUtil, ReportEmailService $reportEmailService)
    {
        parent::__construct();
        $this->transactionUtil = $transactionUtil;
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
            $this->reportEmailService->generateReportAttachment($data, $this->getDay(), $data->interval);
        }
    }

    public function getDay()
    {
        $start_date = now()->toDateString();
        $end_date = now()->toDateString();

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
    }
}
