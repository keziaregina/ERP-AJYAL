<?php

namespace App\Console\Commands\SendReportMail;

use App\User;
use App\Mail\Reporting;
use App\ReportSettings;
use App\Mail\Reporting2;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

        Log::info("REPORT DATA----------------------------");
        Log::info(json_encode($datas,JSON_PRETTY_PRINT));
        Log::info("REPORT DATA----------------------------");
        
        foreach ($datas as $data) {
            if ($data->type === 'purchase_n_sell_report') {
                $user = User::find($data->user_id);
                $image = public_path('img/logo-small.png');
                $filename = 'Purchase_Sales_Report.pdf';

                if (!File::exists(public_path('storage/report'))) {
                    File::makeDirectory(public_path('storage/report'), 0777, true, true);
                }

                if (File::exists(public_path('storage/report/'.$filename))) {
                    File::delete(public_path('storage/report/'.$filename));
                }

              
                $pdf = Pdf::loadView('report_settings/export/purchase_sales', compact('data', 'image', 'user'))
                ->setPaper([0, 0, 70.88, 141.75], 'landscape')
                ->download('Purchase_Sales_Report.pdf');
                
                Mail::to($user->email)->queue(new Reporting($data, $filename));
       
            }
        }
    }

    public function getPurchaseSaleReport()
    {

    }
}
