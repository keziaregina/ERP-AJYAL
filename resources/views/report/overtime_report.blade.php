@extends('layouts.app')
@section('title', __('lang_v1.overtime_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">{{ __('report.overtime') }}</h1>
</section>


<!-- Main content -->
<section class="content">
    <section class="content">
        @component('components.widget', ['class' => 'box-primary'])
            @can('essentials.export_overtime_hour')
                <div class="tw-flex tw-gap-2 tw-mb-4">
                    <a href="{{ route('pdfovertime') }}" class="tw-dw-btn tw-bg-gradient-to-r tw-from-red-600 tw-to-red-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full tw-px-4 tw-py-2 tw-flex tw-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tw-mr-2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        PDF
                    </a>
                    <a href="{{ route('excelovertime') }}" class="tw-dw-btn tw-bg-gradient-to-r tw-from-green-600 tw-to-green-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full tw-px-4 tw-py-2 tw-flex tw-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="tw-mr-2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Excel
                    </a>
                </div>
            @endcan
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="overtime_sheets_table">
                    <thead>
                        <tr>
                            <th>@lang('essentials::lang.employee_name')</th>
                            @for ($i = 1; $i <= $daysInMonth; $i++)
                                <th>{{ $i }}</th>
                            @endfor
                        </tr>
                        @foreach ($overtimeDatas as $key => $employee)
                            <tr>
                                <td>{{ $employee['full_name'] }}</td>
                                @forelse ($employee['overtime_data'] as $key => $value)
                                    <td>{{ $value }}</td>
                                    @empty
                                    <p>-</p>
                                @endforelse
                            </tr>
                        @endforeach
                    </thead>
                </table>
            </div>
        @endcomponent
    </section>
</section>
<!-- /.content -->

@endsection