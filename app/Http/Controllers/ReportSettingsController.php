<?php

namespace App\Http\Controllers;

use App\Mail\Reporting;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\ReportSettings;
use App\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class ReportSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if(request()->ajax()){
            $business_id = request()->session()->get('user.business_id');
            $data = ReportSettings::getUsers($business_id);
            return DataTables::of($data)
            ->addColumn(
            'action',
            ' <a href="{{action(\'App\Http\Controllers\ReportSettingsController@edit\', [$id])}}" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a>
            &nbsp;
            <button type="button" data-href="{{action(\'App\Http\Controllers\ReportSettingsController@destroy\', [$id])}}" class="tw-dw-btn tw-dw-btn-outline tw-dw-btn-xs tw-dw-btn-error delete_report_settings_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>&nbsp;  
                    '
                    )
                    ->removeColumn('id')
                ->rawColumns([0, 3])
                ->make(false);
        }
        return view('report_settings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('report_settings.access')) {
            abort(403, 'Unauthorized action.');
        }
        $users = User::all()->pluck('first_name', 'id');
        $reportTypes = [
            'purchase_n_sell_report' => 'Purchase & Sell Report',
            'contacts_report' => 'Contacts Report',
            'stock_report' => 'Stock Report',
            'tax_report' => 'Tax Report',
            'trending_product_report' => 'Trending Product Report',
            'sales_representative' => 'Sales Representative Report',
            'register_report' => 'Register Report',
            'expense_report' => 'Expense Report',
        ];
        $intervals = ['daily'=>'Daily', 'weekly'=>'Weekly', 'monthly'=>'Monthly', 'yearly'=>'Yearly'];
        return view('report_settings.create', compact('users', 'reportTypes', 'intervals'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('report_settings.access')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $business_id = request()->session()->get('user.business_id');
            $user = User::find($request->user_name);

            $report_settings = new ReportSettings();
            $report_settings->user_id = $request->user_name;
            $report_settings->type = $request->report_type;
            $report_settings->interval = $request->report_interval;
            $report_settings->business_id = $business_id;
            $report_settings->save();
            
            $image = public_path('img/logo-small.png');
            $filename = storage_path('app/public/pdf/report/Ajyal Al-Madina.pdf');
            $directory = dirname($filename);
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            $pdf = Pdf::setPaper('a4', 'landscape')->loadView('pdf', ['data' => $report_settings, 'image' => $image, 'user' => $user ]);
            
            $pdf->save($filename);
            // Mail::to($user->email)->queue(new Reporting($report_settings));

            $output = ['success' => true,
                'msg' => __('report_settings.added_success'),
            ];
        } catch (\Exception $e) {
            \Log::error('Error saving report settings: ' . $e->getMessage());
           $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }
        return redirect('report-settings')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('report_settings.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! auth()->user()->can('report_settings.access')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $users = User::all()->pluck('first_name', 'id');
        $report_type = [
            'purchase_n_sell_report' => 'Purchase & Sell Report',
            'contacts_report' => 'Contacts Report',
            'stock_report' => 'Stock Report',
            'tax_report' => 'Tax Report',
            'trending_product_report' => 'Trending Product Report',
            'sales_representative' => 'Sales Representative Report',
            'register_report' => 'Register Report',
            'expense_report' => 'Expense Report',
        ];
        $intervals = ['daily'=>'Daily', 'weekly'=>'Weekly', 'monthly'=>'Monthly', 'yearly'=>'Yearly'];
        $report_settings = ReportSettings::with('user')->where('business_id', $business_id)->find($id);
        return view('report_settings.edit')->with(compact('report_settings', 'users','report_type','intervals'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('barcode_settings.access')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $report_settings = ReportSettings::find($id);
            $report_settings->user_id = $request->user_name;
            $report_settings->type = $request->report_type;
            $report_settings->interval = $request->report_interval;
            $report_settings->save();
            $output = ['success' => true,
                'msg' => __('report_settings.updated_success'),
            ];
        } catch (\Exception $e) {
            \Log::error('Error updating report settings: ' . $e->getMessage());
            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }
        return redirect('report-settings')->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! auth()->user()->can('barcode_settings.access')) {
            abort(403, 'Unauthorized action.');
        }
        if(request()->ajax()){
            try {
                $report_settings = ReportSettings::find($id);
                if (!$report_settings) {
                    $output = ['success' => false,
                        'msg' => __('messages.not_found'),
                    ];
                }else{
                    $report_settings->delete();
                    $output = ['success' => true,
                        'msg' => __('report_settings.deleted_success'),
                    ];
                }
                
            }
            catch (\Exception $e) {
                \Log::error('Error deleting report settings: ' . $e->getMessage());
                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }
        }
       return $output;
    }
}
