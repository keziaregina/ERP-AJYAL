@extends('layouts.app')
@section('title', __('lang_v1.customer_groups_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('lang_v1.customer_groups_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])

              {!! Form::open(['url' => action([\App\Http\Controllers\ReportController::class, 'getCustomerGroup']), 'method' => 'get', 'id' => 'cg_report_filter_form' ]) !!}
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('cg_customer_group_id', __( 'lang_v1.customer_group_name' ) . ':') !!}
                        {!! Form::select('cg_customer_group_id', $customer_group, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'cg_customer_group_id']); !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('cg_location_id',  __('purchase.business_location') . ':') !!}
                        {!! Form::select('cg_location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('cg_date_range', __('report.date_range') . ':') !!}
                        {!! Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'cg_date_range', 'readonly']); !!}
                    </div>
                </div>

                {!! Form::close() !!}
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-primary'])
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="cg_report_table">
                    <thead>
                        <tr>
                            <th>@lang('lang_v1.customer_group')</th>
                            <th>@lang('report.total_sell')</th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endcomponent
        </div>
    </div>
</section>
<!-- /.content -->

@endsection

@section('javascript')
    
    <script type="text/javascript">
        $(document).ready(function(){
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
            if($('#cg_date_range').length == 1){
                $('#cg_date_range').daterangepicker(
                    dateRangeSettings,
                    function (start, end) {
                        $('#cg_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
                        
                        window.startDate = start.format('YYYY-MM-DD');
                        window.endDate = end.format('YYYY-MM-DD');

                        cg_report_table.ajax.reload();
                    }
                );

                $('#cg_date_range').on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    cg_report_table.ajax.reload();
                });
            }

            // window.canExport initialized ini layout/app.blade.pho
            var export_button = window.canExport;

            cg_report_table = $('#cg_report_table').DataTable({
                buttons: export_button ? pdfButtonsWithDate('cg', '#cg_report_table') : [],
                processing: true,
                serverSide: true,
                fixedHeader:false,
                "ajax": {
                    "url": "/reports/customer-group",
                    "data": function ( d ) {
                        d.location_id = $('#cg_location_id').val();
                        d.customer_group_id = $('#cg_customer_group_id').val();
                        d.start_date = $('#cg_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                        d.end_date = $('#cg_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    }
                },
                columns: [
                    {data: 'name', name: 'CG.name'},
                    {data: 'total_sell', name: 'total_sell', searchable: false}
                ],
                "fnDrawCallback": function (oSettings) {
                    __currency_convert_recursively($('#cg_report_table'));
                }
            });
            
            saveColumnVisibility('cg_report_table', 'colvisState_cg_report');
            loadColumnVisibility('cg_report_table', 'colvisState_cg_report');
            
                //Customer Group report filter
            $('select#cg_location_id, select#cg_customer_group_id, #cg_date_range').change( function(){
                cg_report_table.ajax.reload();
            });
        })
    </script>
    <script>
        const key = 'colvisState_cg_report';
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