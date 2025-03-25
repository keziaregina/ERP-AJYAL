<?php

namespace App\Console\Commands\SendReportMail;

use App\User;
use App\Contact;
use App\Jobs\SendReportEmailJob;
use App\TaxRate;
use App\Transaction;
use App\Mail\Reporting;
use App\ReportSettings;
use App\Mail\Reporting2;
use App\Services\ReportEmailService;
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
    public $reportEmailService;

    public function __construct(TransactionUtil $transactionUtil, ModuleUtil $moduleUtil, ReportEmailService $reportEmailService)
    {
        parent::__construct();
        $this->reportEmailService = $reportEmailService;
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
            $this->reportEmailService->generateReportAttachment($data, $this->getDay(), $data->interval);
        }

        SendReportEmailJob::dispatch();
    }

    public function getDay()
    {
        $start_date = now()->subMonth()->startOfMonth()->toDateString();
        $end_date = now()->subMonth()->endOfMonth()->toDateString();

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
    }
}