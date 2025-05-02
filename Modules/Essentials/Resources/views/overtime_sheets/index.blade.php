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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
@endsection

@section('content')
    @include('essentials::layouts.nav_hrm')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('essentials::lang.overtime_sheets')
            <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('essentials::lang.manage_your_overtime_sheets')</small>
        </h1>
    </section>


    {{-- Main content --}}
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('essentials::lang.manage_your_overtime_sheets')])
                <div class="tw-flex tw-gap-2 tw-mb-4">
                    @can('essentials.export_overtime_hour')
                    
                    <div class="box-tools">
                        <!-- Button trigger modal -->
                        <button type="button" class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full pull-right" data-toggle="modal" data-target="#exportModal">                        
                        @lang('essentials::lang.export_overtime')
                        </button>
                    </div> 
                    @endcan              
                    @can('essentials.edit_overtime_hour')               
                        <div class="box-tools">
                            <!-- Button trigger modal -->
                            <button type="button" class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full pull-right" data-toggle="modal" data-target="#editModal">                        
                            @lang('essentials::lang.edit_overtime_hour')
                            </button>
                        </div>           
                    @endcan  
                </div>    
            @can('essentials.add_overtime_hour')
                @slot('tool')
                    <div class="box-tools">
                        <!-- Button trigger modal -->
                        <button type="button" class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full pull-right" data-toggle="modal" data-target="#exampleModalCenter">
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
            

            {{-- LEGEND --}}
            <div class="tw-mt-6 tw-p-4 tw-bg-gray-50 tw-rounded-lg tw-border tw-border-gray-200">
                <h4 class="tw-text-lg tw-font-semibold tw-mb-3 tw-text-gray-700">@lang('essentials::lang.legend')</h4>
                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4">
                    <div class="tw-flex tw-items-center">
                        <span class="tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-bg-red-100 tw-text-red-800 tw-font-bold tw-rounded-full tw-mr-2">A</span>
                        <span style="padding: 0 10px">@lang('essentials::lang.absent')</span>
                    </div>
                    <div class="tw-flex tw-items-center">
                        <span class="tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-bg-blue-100 tw-text-blue-800 tw-font-bold tw-rounded-full tw-mr-2">VL</span>
                        <span style="padding: 0 10px">@lang('essentials::lang.vacation_leave')</span>
                    </div>
                    <div class="tw-flex tw-items-center">
                        <span class="tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-bg-green-100 tw-text-green-800 tw-font-bold tw-rounded-full tw-mr-2">GE</span>
                        <span style="padding: 0 10px">@lang('essentials::lang.glorious_employee_allowance')</span>
                    </div>
                    <div class="tw-flex tw-items-center">
                        <span class="tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center tw-bg-yellow-100 tw-text-yellow-800 tw-font-bold tw-rounded-full tw-mr-2">SL</span>
                        <span style="padding: 0 10px">@lang('essentials::lang.sick_leave')</span>
                    </div>
                </div>
            </div>

            {{-- Select Employee Glorious --}}
            <div class="tw-mt-6 tw-p-4 tw-bg-gray-50 tw-rounded-lg tw-border tw-border-gray-200">

                <div class="tw-flex tw-justify-between">
                    <h4 class="tw-text-lg tw-font-semibold tw-mb-3 tw-text-gray-700">@lang('essentials::lang.employee_glorious_this_month')</h4>
                    <div class="box-tools">
                        <!-- Button trigger modal -->
                        <button type="button" class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full pull-right" data-toggle="modal" data-target="#selectGEModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                            </svg> 
                            @lang('essentials::lang.select_employee_glorious_this_month')
                        </button>
                    </div>
                </div>

                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4">
                    <label for="user_id">@lang('essentials::lang.employee_name')</label>
                    @if ($gloriousEmployeeThisMonth)
                        <p>{{ $gloriousEmployeeThisMonth->user->first_name }} {{ $gloriousEmployeeThisMonth->user->last_name }}</p>
                    @else
                        <p>-</p>
                    @endif
                </div>
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
                            <div class="mb-2">
                                <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary select-all">
                                    @lang('lang_v1.select_all')
                                </button>
                                <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary deselect-all">
                                    @lang('lang_v1.deselect_all')
                                </button>
                            </div>
                            <select name="user_id[]" id="user_id" class="form-control select2" required multiple style="width: 100%;">
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee['id'] }}">{{ $employee['full_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                                                
                        <div class="form-group">
                            <label for="date">@lang('essentials::lang.date')</label>
                            @if (auth()->user()->hasRole('Admin#1'))
                                <input type="text" name="date" id="date" 
                                class="form-control datepicker"
                                value="{{ date('Y-m-d') }}"
                                max="{{ date('Y-m-d') }}">
                            @else
                                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" disabled>
                            @endif
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
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Overtime</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ action([\Modules\Essentials\Http\Controllers\OvertimeSheetController::class, 'store']) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="user_id">@lang('essentials::lang.employee_name')</label>
                            <div class="mb-2">
                                <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary select-all">
                                    @lang('lang_v1.select_all')
                                </button>
                                <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary deselect-all">
                                    @lang('lang_v1.deselect_all')
                                </button>
                            </div>
                            <select name="user_id[]" id="user_id" class="form-control select2" required multiple style="width: 100%;">
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee['id'] }}">{{ $employee['full_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                                                
                        <div class="form-group">
                            <label for="date">@lang('essentials::lang.date')</label>
                            @if (auth()->user()->hasRole('Admin#1'))
                                <input type="text" name="date" id="date" 
                                class="form-control datepicker"
                                value="{{ date('Y-m-d') }}"
                                max="{{ date('Y-m-d') }}">
                            @else
                                <input type="date" name="date" id="date" class="form-control" value="{{ date('Y-m-d') }}" disabled>
                            @endif
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
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <!-- Modal Export File -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Export Overtime</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="exportForm" method="GET">
                    @csrf
                    <div class="modal-body">
                        @php
                            $months = [
                                '01' => 'January',
                                '02' => 'February',
                                '03' => 'March',
                                '04' => 'April',
                                '05' => 'May',
                                '06' => 'June',
                                '07' => 'July',
                                '08' => 'August',
                                '09' => 'September',
                                '10' => 'October',
                                '11' => 'November',
                                '12' => 'December',
                            ];
                            // $currentMont = \Carbon\Carbon::now()->month;
                            // str_pad($currentMont, 2, '0', STR_PAD_LEFT);                            
                            // $monthsAfterCurrent = array_slice($months, 0, $currentMont, true)

                        @endphp

                        <div class="form-group">
                            <label for="month">{{ __('essentials::lang.month') }}:*</label>
                            <select name="month" id="month" class="form-control" required>
                                @foreach ($months as $key => $value)
                                    <option value="{{ $key }}" {{ $key == date('m') ? 'selected' : '' }} max="{{ date('Y-m-d') }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                                                
                        <div class="form-group">
                            <label for="file_type">{{ __('essentials::lang.format') }}:*</label>
                            <select id="file_type" class="form-control" required>
                                <option value="">-- Pilih Format --</option>
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" onclick="submitExport()" id="btnExport" class="btn btn-primary">Export</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    {{-- Select Employee Glorious --}}
    <div class="modal fade" id="selectGEModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('essentials::lang.select_employee_glorious_this_month')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ action([\Modules\Essentials\Http\Controllers\GloriousEmployeeController::class, 'store']) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="user_id">@lang('essentials::lang.employee_name')</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">@lang('essentials::lang.select_employee')</option>
                                @foreach ($employees as $employee)
                                    @if ($gloriousEmployeeThisMonth && $employee['id'] == $gloriousEmployeeThisMonth->user_id)
                                        <option value="{{ $employee['id'] }}" selected>{{ $employee['full_name'] }}</option>
                                    @else   
                                        <option value="{{ $employee['id'] }}">{{ $employee['full_name'] }}</option>   
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="date">@lang('essentials::lang.date')</label>
                            <input type="text" name="date" id="date" class="form-control" value="{{ date('M, Y') }}" disabled>
                        </div>
                        
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

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function submitExport() {
            var form = document.getElementById('exportForm');
            var fileType = document.getElementById('file_type').value;
            var btn = document.getElementById('btnExport');

            if (!fileType) {
                alert("Pilih format file terlebih dahulu!");
                return;
            }

            if (fileType === 'pdf') {
                form.action = "{{ route('pdfovertime') }}";
            } else if (fileType === 'excel') {
                form.action = "{{ route('excelovertime') }}";
            }

            btn.disabled = true;
            btn.innerText = "{{ __('essentials::lang.download') }}";

            form.submit();

                    setTimeout(function() {
                btn.disabled = false;
                btn.innerText = "Export";
            }, 3000);
        }
    </script>
    <script>
        $(document).ready(function(){
            $('.select-all').click(function(){
                $('#user_id option').prop('selected', true).trigger('change');
            });

            $('.deselect-all').click(function(){
                $('#user_id option').prop('selected', false).trigger('change');
            });

            
            $('#user_id').select2();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                maxDate: "today",
                minDate: firstDayOfMonth,
                disable: [
                    function(date) {
                        return date > today;
                    }
                ],
                @if(!auth()->user()->hasRole('Admin#1'))
                defaultDate: "today",
                @endif
                theme: "material_blue",
                showMonths: 1,
                static: true
            });
        });
    </script>
@endsection