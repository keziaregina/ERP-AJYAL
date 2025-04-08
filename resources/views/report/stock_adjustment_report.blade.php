@extends('layouts.app')
@section('title', __( 'report.stock_adjustment_report' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang( 'report.stock_adjustment_report' )
    </h1>
</section>

<div class="print_section">
    <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
        <img style="margin-bottom: 15px; height: 70px; border-radius: 8px;" src="{{ asset('img/logo-small.png') }}" alt="">
        <h4 style="text-align: center; margin: 0; font-size: 18px">{{ session()->get('business.name') }}</h4>
        <h4 style="text-align: center; margin: 0; font-size: 15px; font-weight: bold">@lang('report.stock_adjustment_report')</h4>
    </div>
    <br>
    <p>Exported At : {{ date('Y-m-d h:i A') }}</p>
    <br>
    <p>Report Start : <span id="startDateStockAjdustment"></span></p>
    <p>Report End : <span id="endDateStockAjdustment"></span></p>
    <br>
    <div class="row">
        <div class="col-sm-6">
            @component('components.widget')
                <table class="table no-border">
                    <tr>
                        <th>{{ __('report.total_normal') }}:</th>
                        <td>
                            <span class="total_normal">
                                <i class="fas fa-sync fa-spin fa-fw"></i>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('report.total_abnormal') }}:</th>
                        <td>
                             <span class="total_abnormal">
                                <i class="fas fa-sync fa-spin fa-fw"></i>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('report.total_stock_adjustment') }}:</th>
                        <td>
                            <span class="total_amount">
                                <i class="fas fa-sync fa-spin fa-fw"></i>
                            </span>
                        </td>
                    </tr>
                </table>
            @endcomponent
        </div>

        <div class="col-sm-6">
            @component('components.widget')
                <table class="table no-border">
                    <tr>
                        <th>{{ __('report.total_recovered') }}:</th>
                        <td>
                             <span class="total_recovered">
                                <i class="fas fa-sync fa-spin fa-fw"></i>
                            </span>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td></tr>
                </table>
            @endcomponent
        </div>
    </div>
    <br>
    <table style="width: 100%;" class="table table-bordered table-striped" id="stock_adjustment_table2">
        <thead>
            <tr>
                <th>@lang('messages.action')</th>
                <th>@lang('messages.date')</th>
                <th>@lang('purchase.ref_no')</th>
                <th>@lang('business.location')</th>
                <th>@lang('stock_adjustment.adjustment_type')</th>
                <th>@lang('stock_adjustment.total_amount')</th>
                <th>@lang('stock_adjustment.total_amount_recovered')</th>
                <th>@lang('stock_adjustment.reason_for_stock_adjustment')</th>
                <th>@lang('lang_v1.added_by')</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Main content -->
<section class="content no-print">
    <div class="row">
        <div class="col-md-3 col-md-offset-7 col-xs-6">
            <div class="input-group">
                <span class="input-group-addon bg-light-blue"><i class="fa fa-map-marker"></i></span>
                 <select class="form-control select2" id="stock_adjustment_location_filter">
                    @foreach($business_locations as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-2 col-xs-6">
            <div class="form-group pull-right">
                <div class="input-group">
                  <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white tw-dw-btn-sm" id="stock_adjustment_date_filter">
                    <span>
                      <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                    </span>
                    <i class="fa fa-caret-down"></i>
                  </button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-6">
            @component('components.widget')
                <table class="table no-border">
                    <tr>
                        <th>{{ __('report.total_normal') }}:</th>
                        <td>
                            <span class="total_normal">
                                <i class="fas fa-sync fa-spin fa-fw"></i>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('report.total_abnormal') }}:</th>
                        <td>
                             <span class="total_abnormal">
                                <i class="fas fa-sync fa-spin fa-fw"></i>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('report.total_stock_adjustment') }}:</th>
                        <td>
                            <span class="total_amount">
                                <i class="fas fa-sync fa-spin fa-fw"></i>
                            </span>
                        </td>
                    </tr>
                </table>
            @endcomponent
        </div>

        <div class="col-sm-6">
            @component('components.widget')
                <table class="table no-border">
                    <tr>
                        <th>{{ __('report.total_recovered') }}:</th>
                        <td>
                             <span class="total_recovered">
                                <i class="fas fa-sync fa-spin fa-fw"></i>
                            </span>
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td></tr>
                </table>
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
        <div class="col-sm-12">
            @component('components.widget', ['class' => 'box-primary', 'title' => __('stock_adjustment.stock_adjustments')])
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="stock_adjustment_table">
                        <thead>
                            <tr>
                                <th>@lang('messages.action')</th>
                                <th>@lang('messages.date')</th>
                                <th>@lang('purchase.ref_no')</th>
                                <th>@lang('business.location')</th>
                                <th>@lang('stock_adjustment.adjustment_type')</th>
                                <th>@lang('stock_adjustment.total_amount')</th>
                                <th>@lang('stock_adjustment.total_amount_recovered')</th>
                                <th>@lang('stock_adjustment.reason_for_stock_adjustment')</th>
                                <th>@lang('lang_v1.added_by')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            @endcomponent
        </div>
    </div>
	

</section>
<!-- /.content -->
@stop
@section('javascript')
    <script src="{{ asset('js/stock_adjustment.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>
    
    <script>
        const key = 'colvisState_stock_adjustment';
        const colvis = localStorage.getItem(key);
        fetch('/api/save-colvis', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ key, colvis })
        });
    </script>
@endsection
