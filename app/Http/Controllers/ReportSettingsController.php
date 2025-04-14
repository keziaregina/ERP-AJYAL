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
            ->editColumn('attachment_lang',
            '<span class="tw-inline-flex tw-items-center tw-px-2 tw-py-1 tw-rounded-md tw-py-0.5 tw-text-xs tw-font-medium tw-bg-blue-500 tw-text-white">
                {{ $attachment_lang }}
            </span>'
            )
                    ->removeColumn('id')
                ->rawColumns([0, 4,2])
                ->make(false);
        }
        return view('report_settings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return array
     */
    public function getReportTypes()
    {
        return [
            'profit_or_loss_report'      => __('report_type.profit_or_loss_report'),
            'purchase_n_sell_report'     => __('report_type.purchase_n_sell_report'),
            // 'contacts_report'            => __('report_type.contacts_report'),
            'customer_n_supplier_report' => __('report_type.customer_n_supplier_report'),
            'customer_group_report'      => __('report_type.customer_group_report'),
            'stock_report'               => __('report_type.stock_report'),
            'stock_adjustment_report'    => __('report_type.stock_adjustment_report'),
            'tax_report'                 => __('report_type.tax_report'),
            'trending_product_report'    => __('report_type.trending_product_report'),
            'items_report'               => __('report_type.items_report'),
            'product_purchase_report'    => __('report_type.product_purchase_report'),
            'product_sell_report'        => __('report_type.product_sell_report'),
            'purchase_payment_report'    => __('report_type.purchase_payment_report'),
            'sales_representative'       => __('report_type.sales_representative'),
            'register_report'            => __('report_type.register_report'),
            'expense_report'             => __('report_type.expense_report'),
            'activity_log'               => __('report_type.activity_log'),
        ];
    }

    public function create()
    {
        if (! auth()->user()->can('report_settings.access')) {
            abort(403, 'Unauthorized action.');
        }
        $users = User::all()->pluck('first_name', 'id');
        $reportTypes = $this->getReportTypes();
        $intervals = [
            'daily'=> __('report_settings.daily'), 
            'weekly'=> __('report_settings.weekly'), 
            'monthly'=> __('report_settings.monthly'), 
            'yearly'=> __('report_settings.yearly')
        ];

        $langs = [
            'en'=> __('report_settings.en'),
            'ar'=> __('report_settings.ar'),
        ];

        return view('report_settings.create', compact('users', 'reportTypes', 'intervals', 'langs'));
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
            $report_settings->attachment_lang = $request->attachment_lang;
            $report_settings->save();
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
        $report_type = $this->getReportTypes();
            
        $intervals = [
            'daily'=> __('report_settings.daily'), 
            'weekly'=> __('report_settings.weekly'), 
            'monthly'=> __('report_settings.monthly'), 
            'yearly'=> __('report_settings.yearly')
        ];
        $langs = [
            'en'=> __('report_settings.en'),
            'ar'=> __('report_settings.ar'),
        ];
        $report_settings = ReportSettings::with('user')->where('business_id', $business_id)->find($id);
        return view('report_settings.edit')->with(compact('report_settings', 'users','report_type','intervals', 'langs'));
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
            $report_settings->attachment_lang = $request->attachment_lang;
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
