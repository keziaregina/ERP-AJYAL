@extends('layouts.app')
@section('title', __('report.sales_representative'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('report.sales_representative')}}</h1>
</section>

<div class="print_section">
    <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
        <img style="margin-bottom: 15px; height: 70px; border-radius: 8px;" src="{{ asset('img/logo-small.png') }}" alt="">
        <h4 style="text-align: center; margin: 0; font-size: 18px">{{ session()->get('business.name') }}</h4>
        <h4 style="text-align: center; margin: 0; font-size: 15px; font-weight: bold">@lang('report.sales_representative')</h4>
    </div>
    <br>
    <p>Exported At : {{ date('Y-m-d h:i A') }}</p>
    <br>
    <p>Report Start : <span id="startDateSalesRepresentative"></span></p>
    <p>Report End : <span id="endDateSalesRepresentative"></span></p>
    <br>

    <table class="table table-bordered table-striped" id="sr_sales_report2" style="width: 100%;">
        <thead>
            <tr>
                <th>@lang('messages.date')</th>
                <th>@lang('sale.invoice_no')</th>
                <th>@lang('sale.customer_name')</th>
                <th>@lang('sale.location')</th>
                <th>@lang('sale.payment_status')</th>
                <th>@lang('sale.total_amount')</th>
                <th>@lang('sale.total_paid')</th>
                <th>@lang('sale.total_remaining')</th>
            </tr>
        </thead>
        <tfoot>
            <tr class="bg-gray font-17 footer-total text-center">
                <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                <td id="sr_footer_payment_status_count"></td>
                <td><span class="display_currency" id="sr_footer_sale_total" data-currency_symbol ="true"></span></td>
                <td><span class="display_currency" id="sr_footer_total_paid" data-currency_symbol ="true"></span></td>
                <td class="text-left"><small>@lang('lang_v1.sell_due') - <span class="display_currency" id="sr_footer_total_remaining" data-currency_symbol ="true"></span><br>@lang('lang_v1.sell_return_due') - <span class="display_currency" id="sr_footer_total_sell_return_due" data-currency_symbol ="true"></span></small></td>
            </tr>
        </tfoot>
    </table>

    <br>
    <table class="table table-bordered table-striped" id="sr_sales_with_commission_table2" style="width: 100%;">
        <thead>
            <tr>
                <th>@lang('messages.date')</th>
                <th>@lang('sale.invoice_no')</th>
                <th>@lang('sale.customer_name')</th>
                <th>@lang('sale.location')</th>
                <th>@lang('sale.payment_status')</th>
                <th>@lang('sale.total_amount')</th>
                <th>@lang('sale.total_paid')</th>
                <th>@lang('sale.total_remaining')</th>
            </tr>
        </thead>
        <tfoot>
            <tr class="bg-gray font-17 footer-total text-center">
                <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                <td id="footer_payment_status_count"></td>
                <td><span class="display_currency" id="footer_sale_total" data-currency_symbol ="true"></span></td>
                <td><span class="display_currency" id="footer_total_paid" data-currency_symbol ="true"></span></td>
                <td class="text-left"><small>@lang('lang_v1.sell_due') - <span class="display_currency" id="footer_total_remaining" data-currency_symbol ="true"></span><br>@lang('lang_v1.sell_return_due') - <span class="display_currency" id="footer_total_sell_return_due" data-currency_symbol ="true"></span></small></td>
            </tr>
        </tfoot>
    </table>

    <br>
    <table class="table table-bordered table-striped" id="sr_expenses_report2" style="width: 100%;">
        <thead>
            <tr>
                <th>@lang('messages.date')</th>
                <th>@lang('purchase.ref_no')</th>
                <th>@lang('expense.expense_category')</th>
                <th>@lang('business.location')</th>
                <th>@lang('sale.payment_status')</th>
                <th>@lang('sale.total_amount')</th>
                <th>@lang('expense.expense_for')</th>
                <th>@lang('expense.expense_note')</th>
            </tr>
        </thead>
        <tfoot>
            <tr class="bg-gray font-17 text-center footer-total">
                <td colspan="4"><strong>@lang('sale.total'):</strong></td>
                <td id="er_footer_payment_status_count"></td>
                <td><span class="display_currency" id="footer_expense_total" data-currency_symbol ="true"></span></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Main content -->
<section class="content no-print ">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
              {!! Form::open(['url' => action([\App\Http\Controllers\ReportController::class, 'getStockReport']), 'method' => 'get', 'id' => 'sales_representative_filter_form' ]) !!}
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('sr_id',  __('report.user') . ':') !!}
                        {!! Form::select('sr_id', $users, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('report.all_users')]); !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('sr_business_id',  __('business.business_location') . ':') !!}
                        {!! Form::select('sr_business_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">

                        {!! Form::label('sr_date_filter', __('report.date_range') . ':') !!}
                        {!! Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'sr_date_filter', 'readonly']); !!}
                    </div>
                </div>

                {!! Form::close() !!}
            @endcomponent
        </div>
    </div>

    <!-- Summary -->
    <div class="row">
        <div class="col-sm-12">
            @component('components.widget', ['title' => __('report.summary')])
                <h3 class="text-muted">
                    {{ __('report.total_sell') }} - {{ __('lang_v1.total_sales_return') }}: 
                    <span id="sr_total_sales">
                        <i class="fas fa-sync fa-spin fa-fw"></i>
                    </span>
                    -
                    <span id="sr_total_sales_return">
                        <i class="fas fa-sync fa-spin fa-fw"></i>
                    </span>
                    =
                    <span id="sr_total_sales_final">
                        <i class="fas fa-sync fa-spin fa-fw"></i>
                    </span>
                </h3>
                <div class="hide" id="total_payment_with_commsn_div">
                    <h3 class="text-muted">
                        {{ __('lang_v1.total_payment_with_commsn') }}: 
                        <span id="total_payment_with_commsn">
                            <i class="fas fa-sync fa-spin fa-fw"></i>
                        </span>
                    </h3>
                </div>
                <div class="hide" id="total_commission_div">
                    <h3 class="text-muted">
                        {{ __('lang_v1.total_sale_commission') }}: 
                        <span id="sr_total_commission">
                            <i class="fas fa-sync fa-spin fa-fw"></i>
                        </span>
                    </h3>
                </div>
                <h3 class="text-muted">
                    {{ __('report.total_expense') }}: 
                    <span id="sr_total_expenses">
                        <i class="fas fa-sync fa-spin fa-fw"></i>
                    </span>
                </h3>
            @endcomponent
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <button class="tw-dw-btn tw-dw-btn-primary tw-text-white pull-right tw-mb-2" aria-label="Print"
                onclick="window.print();" id="print-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-printer">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                    <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                    <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                </svg> @lang('messages.print')
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Custom Tabs -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#sr_sales_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cog" aria-hidden="true"></i> @lang('lang_v1.sales_added')</a>
                    </li>

                    <li>
                        <a href="#sr_commission_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cog" aria-hidden="true"></i> @lang('lang_v1.sales_with_commission')</a>
                    </li>

                    <li>
                        <a href="#sr_expenses_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cog" aria-hidden="true"></i> @lang('expense.expenses')</a>
                    </li>

                    @if(!empty($pos_settings['cmmsn_calculation_type']) && $pos_settings['cmmsn_calculation_type'] == 'payment_received')
                        <li>
                            <a href="#sr_payments_with_cmmsn_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-cog" aria-hidden="true"></i> @lang('lang_v1.payments_with_cmmsn')</a>
                        </li>
                    @endif
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="sr_sales_tab">
                        @include('report.partials.sales_representative_sales')
                    </div>

                    <div class="tab-pane" id="sr_commission_tab">
                        @include('report.partials.sales_representative_commission')
                    </div>

                    <div class="tab-pane" id="sr_expenses_tab">
                        @include('report.partials.sales_representative_expenses')
                    </div>

                    @if(!empty($pos_settings['cmmsn_calculation_type']) && $pos_settings['cmmsn_calculation_type'] == 'payment_received')
                        <div class="tab-pane" id="sr_payments_with_cmmsn_tab">
                            @include('report.partials.sales_representative_payments_with_cmmsn')
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

</section>
<!-- /.content -->
<div class="modal fade view_register" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade payment_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>

    <script>
        const sales = 'colvisState_table#sr_sales_report';
        const saleswc = 'colvisState_table#sr_sales_with_commission_table';
        const expense = 'colvisState_table#sr_expenses_report';

        const colvis1 = localStorage.getItem(sales);
        fetch('/api/save-colvis', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ key: sales, colvis: colvis1 })
        });

        const colvis2 = localStorage.getItem(saleswc);
        fetch('/api/save-colvis', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ key: saleswc, colvis: colvis2 })
        });

        const colvis3 = localStorage.getItem(expense);
        fetch('/api/save-colvis', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ key: expense, colvis: colvis3 })
        });
    </script>
@endsection