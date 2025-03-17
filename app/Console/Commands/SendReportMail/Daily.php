<?php

namespace App\Console\Commands\SendReportMail;

use App\Mail\Reporting;
use App\Mail\Reporting2;
use App\ReportSettings;
use App\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Daily extends Command
{
    public $transactionUtil;

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
            // if ($data->type === 'purchase_n_sell_report') {
                $user = User::find($data->user_id);
                $image = public_path('img/logo-small.png');
                $filename = storage_path('app/public/pdf/report/Ajyal Al-Madina.pdf');
                $directory = dirname($filename);

                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }
                
                $pdf = Pdf::setPaper('a4', 'landscape')->loadView('report_settings/export/purchase_sales', ['data' => $data, 'image' => $image, 'user' => $user ]);
                
                $pdf->save($filename);
                Mail::to($user->email)->queue(new Reporting($data, $filename));
            // } else {
            //     return 'wkwkw';
            // }
        }
    }

    public function getPurchaseSaleReport()
    {

    }
}
