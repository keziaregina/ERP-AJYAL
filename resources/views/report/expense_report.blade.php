@extends('layouts.app')
@section('title', __('report.expense_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('report.expense_report')}}</h1>
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
    <p>Report Start : <span id="startDateExpenses"></span></p>
    <p>Report End : <span id="endDateExpenses"></span></p>
    <br>
</div>
<!-- Main content -->
<section class="content">
    <div class="row no-print">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
              {!! Form::open(['url' => action([\App\Http\Controllers\ReportController::class, 'getExpenseReport']), 'method' => 'get' ]) !!}
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('location_id',  __('purchase.business_location') . ':') !!}
                        {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('category_id', __('category.category').':') !!}
                        {!! Form::select('category', $categories, null, ['placeholder' =>
                        __('report.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'category_id']); !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('trending_product_date_range', __('report.date_range') . ':') !!}
                        {!! Form::text('date_range', null , ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'trending_product_date_range', 'readonly']); !!}
                    </div>
                </div>
                <div class="col-sm-12">
                  <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-dw-btn-sm tw-text-white pull-right">@lang('report.apply_filters')</button>
                </div> 
                {!! Form::close() !!}
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @component('components.widget', ['class' => 'box-primary'])
                {!! $chart->container() !!}
            @endcomponent
        </div>
    </div>

    <div class="row no-print">
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

    <div class="print_section">
        <table class="table" id="expense_report_table2">
            <thead>
                <tr>
                    <th>@lang( 'expense.expense_categories' )</th>
                    <th>@lang( 'report.total_expense' )</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_expense = 0;
                @endphp
                @foreach($expenses as $expense)
                    <tr>
                        <td>{{$expense['category'] ?? __('report.others')}}</td>
                        <td><span class="display_currency" data-currency_symbol="true">{{$expense['total_expense']}}</span></td>
                    </tr>
                    @php
                        $total_expense += $expense['total_expense'];
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>@lang('sale.total')</td>
                    <td><span class="display_currency" data-currency_symbol="true">{{$total_expense}}</span></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="row no-print">
        <div class="col-md-12">
        @component('components.widget', ['class' => 'box-primary'])
            <table class="table" id="expense_report_table">
                <thead>
                    <tr>
                        <th>@lang( 'expense.expense_categories' )</th>
                        <th>@lang( 'report.total_expense' )</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_expense = 0;
                    @endphp
                    @foreach($expenses as $expense)
                        <tr>
                            <td>{{$expense['category'] ?? __('report.others')}}</td>
                            <td><span class="display_currency" data-currency_symbol="true">{{$expense['total_expense']}}</span></td>
                        </tr>
                        @php
                            $total_expense += $expense['total_expense'];
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>@lang('sale.total')</td>
                        <td><span class="display_currency" data-currency_symbol="true">{{$total_expense}}</span></td>
                    </tr>
                </tfoot>
            </table>
        @endcomponent
        </div>
    </div>

</section>
<!-- /.content -->

@endsection

@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
    {!! $chart->script() !!}
@endsection