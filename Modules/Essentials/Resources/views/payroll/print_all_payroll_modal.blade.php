<div class="modal fade" id="print_all_payroll_modal" tabindex="-1" aria-labelledby="printPayrollLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            {!! Form::open([
                'url' => action([\Modules\Essentials\Http\Controllers\PayrollController::class, 'printPayroll']),
                'method' => 'get',
                'id' => 'print_payroll_form',
            ]) !!}

            <div class="modal-body">
                <!-- Employee -->
                <div class="form-group">
                    {!! Form::label('print_employee_ids', __( 'essentials::lang.employee' ) . ':*') !!}

                    <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary select-all">
                        @lang('lang_v1.select_all')
                    </button>
                    <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary deselect-all">
                        @lang('lang_v1.deselect_all')
                    </button>

                    @php
                        // Filter untuk menghapus opsi "None" dari collection
                        $filteredEmployees = $employees->reject(function($value, $key) {
                            return strtolower(trim($value)) === 'none' || strtolower(trim($key)) === 'none';
                        });
                    @endphp

                    {!! Form::select('employee_ids[]', $filteredEmployees, null, [
                        'class' => 'form-control select2',
                        'style' => 'width: 100%;',
                        'multiple',
                        'id' => 'print_employee_ids',
                        'required'
                    ]) !!}
                </div>

                <!-- Department -->
                <div class="form-group">
                    {!! Form::label('print_department_id', __('essentials::lang.department') . ':') !!}
                    {!! Form::select('department_id', $departments, null, [
                        'class' => 'form-control select2',
                        'style' => 'width: 100%;',
                        'id' => 'print_department_id',
                        'placeholder' => __('messages.please_select')
                    ]) !!}
                </div>

                <!-- Designation -->
                <div class="form-group">
                    {!! Form::label('print_designation_id', __('essentials::lang.designation') . ':') !!}
                    {!! Form::select('designation_id', $designations, null, [
                        'class' => 'form-control select2',
                        'style' => 'width: 100%;',
                        'id' => 'print_designation_id',
                        'placeholder' => __('messages.please_select')
                    ]) !!}
                </div>

                <!-- Month-Year -->
                <div class="form-group">
                    {!! Form::label('print_month_year', __( 'essentials::lang.month_year' ) . ':*') !!}
                    <div class="input-group">
                        {!! Form::text('month_year', null, [
                            'class' => 'form-control',
                            'placeholder' => __( 'essentials::lang.month_year' ),
                            'readonly',
                            'id' => 'print_month_year'
                        ]) !!}
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group">
                    {!! Form::label('print_status', __( 'essentials::lang.status' ) . ':') !!}
                    {!! Form::select('status', [
                        'paid' => __('lang_v1.paid'),
                        'due' => __('lang_v1.due'),
                        'partial' => __('lang_v1.partial')
                    ], null, [
                        'class' => 'form-control select2',
                        'style' => 'width: 100%;',
                        'id' => 'print_status',
                        'placeholder' => __('messages.please_select')
                    ]) !!}
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="tw-dw-btn tw-dw-btn-primary tw-text-white" id="print_payroll_btn">
                    @lang('messages.print')
                </button>
                
                <button type="button" class="tw-dw-btn tw-dw-btn-neutral tw-text-white" data-dismiss="modal">
                    @lang('messages.close')
                </button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

@push('modal-scripts')
<script>
$(function () {
    
    function togglePrintButton() {
        var hasValue = false;

        if ($('#print_employee_ids').val() && $('#print_employee_ids').val().length > 0) hasValue = true;
        if ($('#print_department_id').val()) hasValue = true;
        if ($('#print_designation_id').val()) hasValue = true;
        if ($('#print_status').val()) hasValue = true;
        if ($('#print_month_year').val()) hasValue = true;

        $('#print_payroll_btn').prop('disabled', !hasValue);
    }

    togglePrintButton();

    // Event listener untuk semua filter
    $('#print_employee_ids, #print_department_id, #print_designation_id, #print_status').on('change', togglePrintButton);
    $('#print_month_year').on('change keyup', togglePrintButton);

    $('#print_all_payroll_modal').on('shown.bs.modal', function () {
        var $modal = $(this);
        
        // Initialize select2 for all dropdowns
        $modal.find('.select2').select2({
            dropdownParent: $modal
        });

        // Auto-select all employees when modal opens
        setTimeout(function() {
            $('#print_employee_ids > option').prop("selected", true);
            $('#print_employee_ids').trigger("change");
        }, 100);

        // Initialize datepicker for month/year
        $('#print_month_year').datepicker({
            autoclose: true,
            format: 'mm/yyyy',
            minViewMode: "months"
        });
    });

    // Select all employees button
    $('#print_all_payroll_modal').on('click', '.select-all', function (e) {
        e.preventDefault();
        $('#print_employee_ids > option').prop("selected", true);
        $('#print_employee_ids').trigger("change");
    });

    // Deselect all employees button
    $('#print_all_payroll_modal').on('click', '.deselect-all', function (e) {
        e.preventDefault();
        $('#print_employee_ids > option').prop("selected", false);
        $('#print_employee_ids').trigger("change");
    });

    // Print button click handler with validation check
    $('#print_payroll_btn').on('click', function (e) {
        e.preventDefault();

        var $form = $('#print_payroll_form');
        var $btn = $(this);
        var selectedEmployees = $('#print_employee_ids').val();

        // Validate employee selection
        if (!selectedEmployees || selectedEmployees.length === 0) {
            if (typeof toastr !== 'undefined') {
                toastr.error('Please select at least one employee');
            } else {
                alert('Please select at least one employee');
            }
            return false;
        }

        var url = $form.attr('action');
        var formData = $form.serializeArray();

        // Add check_only parameter for validation
        formData.push({ name: 'check_only', value: 1 });

        $.ajax({
            method: 'GET',
            url: url,
            data: formData,
            beforeSend: function () {
                $btn.prop('disabled', true).text('Checking...');
            },
            success: function (result, textStatus, xhr) {
                // Check if response is JSON
                var contentType = xhr.getResponseHeader('content-type') || '';
                
                if (contentType.indexOf('json') > -1) {
                    // Handle JSON response (validation error)
                    if (result.success === false) {
                        if (typeof toastr !== 'undefined') {
                            toastr.error(result.msg);
                        } else {
                            alert(result.msg);
                        }
                        return;
                    }
                }
                
                // If we get here, validation passed or no validation response
                // Remove check_only parameter and open print URL
                var printData = $form.serializeArray().filter(function(item) {
                    return item.name !== 'check_only';
                });
                
                var printUrl = url + '?' + $.param(printData);
                window.open(printUrl, '_blank');
            },
            error: function (xhr, textStatus, errorThrown) {
                // Check if it's a validation error or actual error
                if (xhr.status === 422 || xhr.status === 400) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.msg) {
                            if (typeof toastr !== 'undefined') {
                                toastr.error(response.msg);
                            } else {
                                alert(response.msg);
                            }
                            return;
                        }
                    } catch (e) {
                        // Not JSON, continue to generic error
                    }
                }
                
                var errorMsg = "@lang('messages.something_went_wrong')";
                if (typeof toastr !== 'undefined') {
                    toastr.error(errorMsg);
                } else {
                    alert(errorMsg);
                }
            },
            complete: function () {
                $btn.prop('disabled', false).text('@lang("messages.print")');
            }
        });
    });

    // Reset modal when closed
    $('#print_all_payroll_modal').on('hidden.bs.modal', function () {
        $('#print_payroll_btn').prop('disabled', false).text('@lang("messages.print")');
        
        $('#print_employee_ids').val(null).trigger('change');
        $('#print_department_id').val(null).trigger('change');
        $('#print_designation_id').val(null).trigger('change');
        $('#print_status').val(null).trigger('change');
        $('#print_month_year').val('');
    });

    // Employee selection counter (for debugging/logging)
    $('#print_employee_ids').on('change', function() {
        var selectedCount = $(this).val() ? $(this).val().length : 0;
        var totalCount = $(this).find('option').length;
        
        if (selectedCount === 0) {
            console.log('No employees selected');
        } else if (selectedCount === totalCount) {
            console.log('All employees selected (' + selectedCount + ')');
        } else {
            console.log(selectedCount + ' of ' + totalCount + ' employees selected');
        }
    });
});
</script>
@endpush