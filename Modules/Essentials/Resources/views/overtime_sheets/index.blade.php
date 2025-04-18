@extends('layouts.app')

@section('title', __('essentials::lang.overtime_sheets'))

@section('css')
    <style>
        .modal-header {
            display: flex;
            justify-content: space-between;
            width: 100%;
            /* background-color: #007bff; */
        }        
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('essentials::lang.overtime_sheets')
            <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('essentials::lang.manage_your_overtime_sheets')</small>
        </h1>
    </section>


    {{-- Main content --}}
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('essentials::lang.manage_your_overtime_sheets')])
            <a href="{{ route('pdfovertime') }}">PDF</a>
            <a href="{{ route('excelovertime') }}">Excel</a>
            @slot('tool')
                <div class="box-tools">
                    {{-- <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full pull-right"
                        href="{{action([\App\Http\Controllers\ReportSettingsController::class, 'create'])}}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg> @lang('essentials::lang.add_new_overtime')
                    </a> --}}

                    <!-- Button trigger modal -->
                    <button type="button" class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full pull-right" data-toggle="modal" data-target="#exampleModalCenter">
                    {{-- <button type="button" class="btn btn-primary btn-modal" data-toggle="modal" data-target="#exampleModalCenter"> --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 5l0 14" />
                        <path d="M5 12l14 0" />
                        </svg> 
                        @lang('essentials::lang.add_new_overtime')
                    </button>

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
                            {{-- <th>@lang('messages.action')</th> --}}
                        </tr>
                        {{-- @dd($employees); --}}
                        {{-- @dd($overtimeData); --}}
                        {{-- @dd($overtimeData); --}}
                        @foreach ($overtimeDatas as $key => $employee)
                        {{-- @dd($overtimeData);  --}}
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

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Overtime</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ action([\Modules\Essentials\Http\Controllers\OvertimeSheetController::class, 'store']) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="user_id">@lang('essentials::lang.employee_name')</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">@lang('essentials::lang.select_employee')</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date">@lang('essentials::lang.date')</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" disabled>
                        </div>
                            
                        <div class="form-group">
                            <label for="overtime_hours">@lang('essentials::lang.overtime_hours')</label>
                            <select name="overtime_hours" id="overtime_hours" class="form-control" required>
                                <option value="">@lang('essentials::lang.select_overtime_hours')</option>
                                @foreach ($overtimeOptions as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                        </div> --}}

                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
        
@endsection