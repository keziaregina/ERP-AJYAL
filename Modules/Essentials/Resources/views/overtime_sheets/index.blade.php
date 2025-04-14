@extends('layouts.app')

@section('title', __('essentials::lang.overtime_sheets'))

@section('content')
    {{-- <h1>TEST</h1> --}}
    {{-- @dd($employees) --}}

    
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('essentials::lang.overtime_sheets')
            <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('essentials::lang.manage_your_overtime_sheets')</small>
        </h1>
    </section>


    {{-- Main content --}}
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('essentials::lang.manage_your_overtime_sheets')])
            @slot('tool')
                <div class="box-tools">
                    <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full pull-right"
                        href="{{action([\App\Http\Controllers\ReportSettingsController::class, 'create'])}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg> @lang('report_settings.add_new_setting')
                    </a>
                </div>
            @endslot
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="overtime_sheets_table">
                    <thead>
                        <tr>
                            <th>@lang('essentials::lang.employee_name')</th>
                            {{-- <th>@lang('report_settings.report_type')</th>
                            <th>@lang('report_settings.attachment_lang')</th>
                            <th>@lang('report_settings.report_interval')</th> --}}
                            @for ($i = 1; $i <= $daysInMonth; $i++)
                                <th>{{ $i }}</th>
                            @endfor
                            <th>@lang('messages.action')</th>
                        </tr>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->full_name }}</td>
                            </tr>
                        @endforeach
                    </thead>
                </table>
            </div>
        @endcomponent
    </section>
    
@endsection