@extends('layouts.app')
@section('title', __('report.profit_loss'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('report.profit_loss')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="print_section">
            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                <img style="margin-bottom: 15px; height: 70px; border-radius: 8px;" src="{{ asset('img/logo-small.png') }}" alt="">
                <h4 style="text-align: center; margin: 0; font-size: 18px">{{ session()->get('business.name') }}</h4>
                <h4 style="text-align: center; margin: 0; font-size: 15px; font-weight: bold">@lang('report.profit_loss')</h4>
            </div>
            <br>
            <p>Exported At : {{ date('Y-m-d h:i A') }}</p>
            <br>
            <p>Report Start : <span id="startDateProfitLoss"></span></p>
            <p>Report End : <span id="endDateProfitLoss"></span></p>
        </div>

        <div class="row no-print">
            <div class="col-md-3 col-md-offset-7 col-xs-6">
                <div class="input-group">
                    <span class="input-group-addon bg-light-blue"><i class="fa fa-map-marker"></i></span>
                    <select class="form-control select2" id="profit_loss_location_filter">
                        @foreach ($business_locations as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-xs-6">
                <div class="form-group pull-right">
                    <div class="input-group">
                        <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white tw-dw-btn-sm" id="profit_loss_date_filter">
                            <span>
                                <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                            </span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="pl_data_div">
            </div>
        </div>


        <div class="row no-print">
            <div class="col-sm-12 tw-mb-2">
                {{-- <button type="button" class="btn btn-primary pull-right" 
            aria-label="Print" onclick="window.print();"
            ><i class="fa fa-print"></i> @lang( 'messages.print' )</button> --}}

                <button class="tw-dw-btn tw-dw-btn-primary tw-text-white pull-right" aria-label="Print"
                    onclick="window.print();">
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
        <div class="row no-print">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#profit_by_products" data-toggle="tab" aria-expanded="true"><i class="fa fa-cubes"
                                    aria-hidden="true"></i> @lang('lang_v1.profit_by_products')</a>
                        </li>

                        <li>
                            <a href="#profit_by_categories" data-toggle="tab" aria-expanded="true"><i class="fa fa-tags"
                                    aria-hidden="true"></i> @lang('lang_v1.profit_by_categories')</a>
                        </li>

                        <li>
                            <a href="#profit_by_brands" data-toggle="tab" aria-expanded="true"><i class="fa fa-diamond"
                                    aria-hidden="true"></i> @lang('lang_v1.profit_by_brands')</a>
                        </li>

                        <li>
                            <a href="#profit_by_locations" data-toggle="tab" aria-expanded="true"><i
                                    class="fa fa-map-marker" aria-hidden="true"></i> @lang('lang_v1.profit_by_locations')</a>
                        </li>

                        <li>
                            <a href="#profit_by_invoice" data-toggle="tab" aria-expanded="true"><i class="fa fa-file-alt"
                                    aria-hidden="true"></i> @lang('lang_v1.profit_by_invoice')</a>
                        </li>

                        <li>
                            <a href="#profit_by_date" data-toggle="tab" aria-expanded="true"><i class="fa fa-calendar"
                                    aria-hidden="true"></i> @lang('lang_v1.profit_by_date')</a>
                        </li>
                        <li>
                            <a href="#profit_by_customer" data-toggle="tab" aria-expanded="true"><i class="fa fa-user"
                                    aria-hidden="true"></i> @lang('lang_v1.profit_by_customer')</a>
                        </li>
                        <li>
                            <a href="#profit_by_day" data-toggle="tab" aria-expanded="true"><i class="fa fa-calendar"
                                    aria-hidden="true"></i> @lang('lang_v1.profit_by_day')</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="profit_by_products">
                            @include('report.partials.profit_by_products')
                        </div>

                        <div class="tab-pane" id="profit_by_categories">
                            @include('report.partials.profit_by_categories')
                        </div>

                        <div class="tab-pane" id="profit_by_brands">
                            @include('report.partials.profit_by_brands')
                        </div>

                        <div class="tab-pane" id="profit_by_locations">
                            @include('report.partials.profit_by_locations')
                        </div>

                        <div class="tab-pane" id="profit_by_invoice">
                            @include('report.partials.profit_by_invoice')
                        </div>

                        <div class="tab-pane" id="profit_by_date">
                            @include('report.partials.profit_by_date')
                        </div>

                        <div class="tab-pane" id="profit_by_customer">
                            @include('report.partials.profit_by_customer')
                        </div>

                        <div class="tab-pane" id="profit_by_day">

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            function saveColumnVisibility(tableId, storageKey) {
                $('#' + tableId).on('column-visibility.dt', function (e, settings, column, state) {
                    let colvisState = JSON.parse(localStorage.getItem(storageKey)) || {};
                    colvisState[column] = state;
                    localStorage.setItem(storageKey, JSON.stringify(colvisState));
                });
            }
            
            function loadColumnVisibility(tableId, storageKey) {
                let colvisState = JSON.parse(localStorage.getItem(storageKey));
                if (colvisState) {
                    $.each(colvisState, function (index, state) {
                        $('#' + tableId).DataTable().column(index).visible(state);
                    });
                }
            }

    // window.canExport initialized ini layout/app.blade.php
    var export_button = window.canExport;

            profit_by_products_table = $('#profit_by_products_table').DataTable({
                buttons: export_button ? pdfButtonsWithDate('profitloss_byproduct', '#profit_by_products_table') : [],
                processing: true,
                serverSide: true,
                fixedHeader:false,
                "ajax": {
                    "url": "/reports/get-profit/product",
                    "data": function(d) {
                        d.start_date = $('#profit_loss_date_filter')
                            .data('daterangepicker')
                            .startDate.format('YYYY-MM-DD');
                        d.end_date = $('#profit_loss_date_filter')
                            .data('daterangepicker')
                            .endDate.format('YYYY-MM-DD');
                        d.location_id = $('#profit_loss_location_filter').val();
                    }
                },
                columns: [{
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'gross_profit',
                        "searchable": false
                    },
                ],
                footerCallback: function(row, data, start, end, display) {
                    var total_profit = 0;
                    for (var r in data) {
                        total_profit += $(data[r].gross_profit).data('orig-value') ?
                            parseFloat($(data[r].gross_profit).data('orig-value')) : 0;
                    }

                    $('#profit_by_products_table .footer_total').html(__currency_trans_from_en(
                        total_profit));
                }
            });

            saveColumnVisibility('profit_by_products_table', 'colvisState_profit_by_products');
            loadColumnVisibility('profit_by_products_table', 'colvisState_profit_by_products');

            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var target = $(e.target).attr('href');
                if (target == '#profit_by_categories') {
                    if (typeof profit_by_categories_datatable == 'undefined') {
                        profit_by_categories_datatable = $('#profit_by_categories_table').DataTable({
                            buttons: export_button ? pdfButtonsWithDate('profitloss_bycat', '#profit_by_categories_table') : [],
                            processing: true,
                            serverSide: true,
                            fixedHeader:false,
                            "ajax": {
                                "url": "/reports/get-profit/category",
                                "data": function(d) {
                                    d.start_date = $('#profit_loss_date_filter')
                                        .data('daterangepicker')
                                        .startDate.format('YYYY-MM-DD');
                                    d.end_date = $('#profit_loss_date_filter')
                                        .data('daterangepicker')
                                        .endDate.format('YYYY-MM-DD');
                                    d.location_id = $('#profit_loss_location_filter').val();
                                }
                            },
                            columns: [{
                                    data: 'category',
                                    name: 'C.name'
                                },
                                {
                                    data: 'gross_profit',
                                    "searchable": false
                                },
                            ],
                            footerCallback: function(row, data, start, end, display) {
                                var total_profit = 0;
                                for (var r in data) {
                                    total_profit += $(data[r].gross_profit).data('orig-value') ?
                                        parseFloat($(data[r].gross_profit).data('orig-value')) :
                                        0;
                                }

                                $('#profit_by_categories_table .footer_total').html(
                                    __currency_trans_from_en(total_profit));
                            },
                        });

                        saveColumnVisibility('profit_by_categories_table', 'colvisState_profit_by_categories');
                        loadColumnVisibility('profit_by_categories_table', 'colvisState_profit_by_categories');
                    } else {
                        profit_by_categories_datatable.ajax.reload();
                    }
                } else if (target == '#profit_by_brands') {
                    if (typeof profit_by_brands_datatable == 'undefined') {
                        profit_by_brands_datatable = $('#profit_by_brands_table').DataTable({
                            buttons: export_button ? pdfButtonsWithDate('profitloss_bybrand', '#profit_by_brands_table') : [],
                            processing: true,
                            serverSide: true,
                            fixedHeader:false,
                            "ajax": {
                                "url": "/reports/get-profit/brand",
                                "data": function(d) {
                                    d.start_date = $('#profit_loss_date_filter')
                                        .data('daterangepicker')
                                        .startDate.format('YYYY-MM-DD');
                                    d.end_date = $('#profit_loss_date_filter')
                                        .data('daterangepicker')
                                        .endDate.format('YYYY-MM-DD');
                                    d.location_id = $('#profit_loss_location_filter').val();
                                }
                            },
                            columns: [{
                                    data: 'brand',
                                    name: 'B.name'
                                },
                                {
                                    data: 'gross_profit',
                                    "searchable": false
                                },
                            ],
                            footerCallback: function(row, data, start, end, display) {
                                var total_profit = 0;
                                for (var r in data) {
                                    total_profit += $(data[r].gross_profit).data('orig-value') ?
                                        parseFloat($(data[r].gross_profit).data('orig-value')) :
                                        0;
                                }

                                $('#profit_by_brands_table .footer_total').html(
                                    __currency_trans_from_en(total_profit));
                            },
                        });

                        saveColumnVisibility('profit_by_brands_table', 'colvisState_profit_by_brands');
                        loadColumnVisibility('profit_by_brands_table', 'colvisState_profit_by_brands');
                    } else {
                        profit_by_brands_datatable.ajax.reload();
                    }
                } else if (target == '#profit_by_locations') {
                    if (typeof profit_by_locations_datatable == 'undefined') {
                        profit_by_locations_datatable = $('#profit_by_locations_table').DataTable({
                            buttons: export_button ? pdfButtonsWithDate('profitloss_bylocations', '#profit_by_locations_table') : [],
                            processing: true,
                            serverSide: true,
                            fixedHeader:false,
                            "ajax": {
                                "url": "/reports/get-profit/location",
                                "data": function(d) {
                                    d.start_date = $('#profit_loss_date_filter')
                                        .data('daterangepicker')
                                        .startDate.format('YYYY-MM-DD');
                                    d.end_date = $('#profit_loss_date_filter')
                                        .data('daterangepicker')
                                        .endDate.format('YYYY-MM-DD');
                                    d.location_id = $('#profit_loss_location_filter').val();
                                }
                            },
                            columns: [{
                                    data: 'location',
                                    name: 'L.name'
                                },
                                {
                                    data: 'gross_profit',
                                    "searchable": false
                                },
                            ],
                            footerCallback: function(row, data, start, end, display) {
                                var total_profit = 0;
                                for (var r in data) {
                                    total_profit += $(data[r].gross_profit).data('orig-value') ?
                                        parseFloat($(data[r].gross_profit).data('orig-value')) :
                                        0;
                                }

                                $('#profit_by_locations_table .footer_total').html(
                                    __currency_trans_from_en(total_profit));
                            },
                        });

                        saveColumnVisibility('profit_by_locations_table', 'colvisState_profit_by_locations');
                        loadColumnVisibility('profit_by_locations_table', 'colvisState_profit_by_locations');
                    } else {
                        profit_by_locations_datatable.ajax.reload();
                    }
                } else if (target == '#profit_by_invoice') {
                    if (typeof profit_by_invoice_datatable == 'undefined') {
                        profit_by_invoice_datatable = $('#profit_by_invoice_table').DataTable({
                            buttons: export_button ? pdfButtonsWithDate('profitloss_byinvoice', '#profit_by_invoice_table') : [],
                            processing: true,
                            serverSide: true,
                            fixedHeader:false,
                            "ajax": {
                                "url": "/reports/get-profit/invoice",
                                "data": function(d) {
                                    d.start_date = $('#profit_loss_date_filter')
                                        .data('daterangepicker')
                                        .startDate.format('YYYY-MM-DD');
                                    d.end_date = $('#profit_loss_date_filter')
                                        .data('daterangepicker')
                                        .endDate.format('YYYY-MM-DD');
                                    d.location_id = $('#profit_loss_location_filter').val();
                                }
                            },
                            columns: [{
                                    data: 'invoice_no',
                                    name: 'sale.invoice_no'
                                },
                                {
                                    data: 'gross_profit',
                                    "searchable": false
                                },
                            ],
                            footerCallback: function(row, data, start, end, display) {
                                var total_profit = 0;
                                for (var r in data) {
                                    total_profit += $(data[r].gross_profit).data('orig-value') ?
                                        parseFloat($(data[r].gross_profit).data('orig-value')) :
                                        0;
                                }

                                $('#profit_by_invoice_table .footer_total').html(
                                    __currency_trans_from_en(total_profit));
                            },
                        });

                        saveColumnVisibility('profit_by_invoice_table', 'colvisState_profit_by_invoice');
                        loadColumnVisibility('profit_by_invoice_table', 'colvisState_profit_by_invoice');
                    } else {
                        profit_by_invoice_datatable.ajax.reload();
                    }
                } else if (target == '#profit_by_date') {
                    if (typeof profit_by_date_datatable == 'undefined') {
                        profit_by_date_datatable = $('#profit_by_date_table').DataTable({
                            buttons: export_button ? pdfButtonsWithDate('profitloss_bydate', '#profit_by_date_table') : [],
                            processing: true,
                            serverSide: true,
                            fixedHeader:false,
                            "ajax": {
                                "url": "/reports/get-profit/date",
                                "data": function(d) {
                                    d.start_date = $('#profit_loss_date_filter')
                                        .data('daterangepicker')
                                        .startDate.format('YYYY-MM-DD');
                                    d.end_date = $('#profit_loss_date_filter')
                                        .data('daterangepicker')
                                        .endDate.format('YYYY-MM-DD');
                                    d.location_id = $('#profit_loss_location_filter').val();
                                }
                            },
                            columns: [{
                                    data: 'transaction_date',
                                    name: 'sale.transaction_date'
                                },
                                {
                                    data: 'gross_profit',
                                    "searchable": false
                                },
                            ],
                            footerCallback: function(row, data, start, end, display) {
                                var total_profit = 0;
                                for (var r in data) {
                                    total_profit += $(data[r].gross_profit).data('orig-value') ?
                                        parseFloat($(data[r].gross_profit).data('orig-value')) :
                                        0;
                                }

                                $('#profit_by_date_table .footer_total').html(
                                    __currency_trans_from_en(total_profit));
                            },
                        });

                        saveColumnVisibility('profit_by_date_table', 'colvisState_profit_by_date');
                        loadColumnVisibility('profit_by_date_table', 'colvisState_profit_by_date');
                    } else {
                        profit_by_date_datatable.ajax.reload();
                    }
                } else if (target == '#profit_by_customer') {
                    if (typeof profit_by_customers_table == 'undefined') {
                        profit_by_customers_table = $('#profit_by_customer_table').DataTable({
                            buttons: export_button ? pdfButtonsWithDate('profitloss_bycustomer', '#profit_by_customer_table') : [],
                            processing: true,
                            serverSide: true,
                            fixedHeader:false,
                            "ajax": {
                                "url": "/reports/get-profit/customer",
                                "data": function(d) {
                                    d.start_date = $('#profit_loss_date_filter')
                                        .data('daterangepicker')
                                        .startDate.format('YYYY-MM-DD');
                                    d.end_date = $('#profit_loss_date_filter')
                                        .data('daterangepicker')
                                        .endDate.format('YYYY-MM-DD');
                                    d.location_id = $('#profit_loss_location_filter').val();
                                }
                            },
                            columns: [{
                                    data: 'customer',
                                    name: 'CU.name'
                                },
                                {
                                    data: 'gross_profit',
                                    "searchable": false
                                },
                            ],
                            footerCallback: function(row, data, start, end, display) {
                                var total_profit = 0;
                                for (var r in data) {
                                    total_profit += $(data[r].gross_profit).data('orig-value') ?
                                        parseFloat($(data[r].gross_profit).data('orig-value')) :
                                        0;
                                }

                                $('#profit_by_customer_table .footer_total').html(
                                    __currency_trans_from_en(total_profit));
                            },
                        });

                        saveColumnVisibility('profit_by_customers_table', 'colvisState_profit_by_customers');
                        loadColumnVisibility('profit_by_customers_table', 'colvisState_profit_by_customers');
                    } else {
                        profit_by_customers_table.ajax.reload();
                    }
                } else if (target == '#profit_by_day') {
                    var start_date = $('#profit_loss_date_filter')
                        .data('daterangepicker')
                        .startDate.format('YYYY-MM-DD');

                    var end_date = $('#profit_loss_date_filter')
                        .data('daterangepicker')
                        .endDate.format('YYYY-MM-DD');
                    var location_id = $('#profit_loss_location_filter').val();

                    var url = '/reports/get-profit/day?start_date=' + start_date + '&end_date=' + end_date +
                        '&location_id=' + location_id;
                    $.ajax({
                        url: url,
                        dataType: 'html',
                        success: function(result) {
                            $('#profit_by_day').html(result);
                            profit_by_days_table = $('#profit_by_day_table').DataTable({
                                buttons: export_button ? pdfButtonsWithDate('profitloss_byday', '#profit_by_day_table') : [],
                                "searching": false,
                                'paging': false,
                                'ordering': false,
                            });
                            var total_profit = sum_table_col($('#profit_by_day_table'),
                                'gross-profit');
                            $('#profit_by_day_table .footer_total').text(total_profit);
                            __currency_convert_recursively($('#profit_by_day_table'));

                            saveColumnVisibility('profit_by_day_table', 'colvisState_profit_by_day');
                            loadColumnVisibility('profit_by_day_table', 'colvisState_profit_by_day');
                        },
                    });
                } else if (target == '#profit_by_products') {
                    profit_by_products_table.ajax.reload();
                }
            });
        });
    </script>

@endsection
