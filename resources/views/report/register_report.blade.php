@extends('layouts.app')
@section('title', __('report.register_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('report.register_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
              {!! Form::open(['url' => action([\App\Http\Controllers\ReportController::class, 'getStockReport']), 'method' => 'get', 'id' => 'register_report_filter_form' ]) !!}
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('register_user_id',  __('report.user') . ':') !!}
                        {!! Form::select('register_user_id', $users, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('report.all_users')]); !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('register_status',  __('sale.status') . ':') !!}
                        {!! Form::select('register_status', ['open' => __('cash_register.open'), 'close' => __('cash_register.close')], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('report.all')]); !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('register_report_date_range', __('report.date_range') . ':') !!}
                        {!! Form::text('register_report_date_range', null , ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'register_report_date_range', 'readonly']); !!}
                    </div>
                </div>
                {!! Form::close() !!}
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-primary'])
                <table class="table table-bordered table-striped" id="register_report_table">
                    <thead>
                        <tr>
                            <th>@lang('report.open_time')</th>
                            <th>@lang('report.close_time')</th>
                            <th>@lang('sale.location')</th>
                            <th>@lang('report.user')</th>
                            <th>@lang('cash_register.total_card_slips')</th>
                            <th>@lang('cash_register.total_cheques')</th>
                            {{-- @if ($paymentOptions?->cash?->is_enabled) --}}
                            <th>@lang('cash_register.total_cash')</th>
                            {{-- @endif --}}
                            <th>@lang('lang_v1.total_bank_transfer')</th>
                            <th>@lang('lang_v1.total_advance_payment')</th>
                            {{-- @dd($paymentOptions?->card?->is_enabled) --}}
                            <th>{{$payment_types['custom_pay_1']}}</th>
                            <th>{{$payment_types['custom_pay_2']}}</th>
                            <th>{{$payment_types['custom_pay_3']}}</th>
                            <th>{{$payment_types['custom_pay_4']}}</th>
                            <th>{{$payment_types['custom_pay_5']}}</th>
                            <th>{{$payment_types['custom_pay_6']}}</th>
                            <th>{{$payment_types['custom_pay_7']}}</th>
                            <th>@lang('cash_register.other_payments')</th>
                            <th>@lang('sale.total')</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="bg-gray font-17 text-center footer-total">
                            <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                            <td class="footer_total_card_payment"></td>
                            <td class="footer_total_cheque_payment"></td>
                            {{-- @if ($paymentOptions?->cash?->is_enabled) --}}
                            <td class="footer_total_cash_payment"></td>
                            {{-- @endif --}}
                            <td class="footer_total_bank_transfer_payment"></td>
                            <td class="footer_total_advance_payment"></td>'
                            <td class="footer_total_custom_pay_1"></td>
                            <td class="footer_total_custom_pay_2"></td>
                            <td class="footer_total_custom_pay_3"></td>
                            <td class="footer_total_custom_pay_4"></td>
                            <td class="footer_total_custom_pay_5"></td>
                            <td class="footer_total_custom_pay_6"></td>
                            <td class="footer_total_custom_pay_7"></td>
                            <td class="footer_total_other_payments"></td>
                            <td class="footer_total"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            @endcomponent
        </div>
    </div>
</section>
<!-- /.content -->
<div class="modal fade view_register" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>

    <script>
        const key = 'colvisState_register_report';
        let paymentOptions = {!! json_encode($paymentOptions) !!}
        const colvisString = localStorage.getItem(key);
        
        let colvis = {};
        if (colvisString) {
            try {
                colvis = JSON.parse(colvisString);
            } catch (e) {
                console.log('Error parsing colvis:', e);
                colvis = {};
            }
        }

        let setOptions = {
            "4": paymentOptions.card?.is_enabled || false,       
            "5": paymentOptions.cheque?.is_enabled || false,      
            "6": paymentOptions.cash?.is_enabled || false,       
            "7": paymentOptions.bank_transfer?.is_enabled || false, 
            "8": paymentOptions.advance?.is_enabled || false,   
            "9": paymentOptions.custom_pay_1?.is_enabled || false,   
            "10": paymentOptions.custom_pay_2?.is_enabled || false,  
            "11": paymentOptions.custom_pay_3?.is_enabled || false, 
            "12": paymentOptions.custom_pay_4?.is_enabled || false, 
            "13": paymentOptions.custom_pay_5?.is_enabled || false,  
            "14": paymentOptions.custom_pay_6?.is_enabled || false, 
            "15": paymentOptions.custom_pay_7?.is_enabled || false, 
            "16": paymentOptions.other?.is_enabled || false,
        }

        let convertedSetOptions = {};
        for (let key in setOptions) {
            convertedSetOptions[key] = setOptions[key] === "1" ? true : false;
        }

        let mergedOpt = {...colvis, ...convertedSetOptions}
        let mergedOptString = JSON.stringify(mergedOpt);

        localStorage.setItem(key,mergedOptString);

        fetch('/api/save-colvis', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ key, mergedOptString })
        });
    </script>
@endsection