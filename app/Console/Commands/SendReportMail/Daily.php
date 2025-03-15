<?php

namespace App\Console\Commands\SendReportMail;

use App\Mail\Reporting;
use App\Mail\Reporting2;
use App\ReportSettings;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Daily extends Command
{
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
            $email = User::find($data->user_id);
            if ($data->type === 'tax_report') {
                Mail::to($email)->queue(new Reporting($data));
            } else {
                Mail::to($email)->queue(new Reporting2($data));
            }
        }

        return 'Berhasil';
    }
}
