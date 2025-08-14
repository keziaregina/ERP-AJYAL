<div class="modal fade" id="print_all_payroll_modal" tabindex="-1" aria-labelledby="printPayrollLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            {!! Form::open([
                'url' => action([\Modules\Essentials\Http\Controllers\PayrollController::class, 'printPayroll']),
                'method' => 'get',
                'id' => 'print_payroll_form',
                'target' => '_blank'
            ]) !!}

            <div class="modal-body">
                <!-- Employee -->
                <div class="form-group">
                    {!! Form::label('print_employee_ids', __( 'essentials::lang.employee' ) . ':*') !!}
                    
                    <!-- Tombol Select All dan Deselect All (seperti asli) -->
                    <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary select-all">
                        @lang('lang_v1.select_all')
                    </button>
                    <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary deselect-all">
                        @lang('lang_v1.deselect_all')
                    </button>
                    
                    {!! Form::select('employee_ids[]', $employees, null, [
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
                            'required',
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
                <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white" id="print_payroll_btn">
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
    // Ketika modal dibuka
    $('#print_all_payroll_modal').on('shown.bs.modal', function () {
        var $modal = $(this);
        
        // Select2 khusus modal
        $modal.find('.select2').select2({
            dropdownParent: $modal
        });

        // Auto select all employees saat modal dibuka
        setTimeout(function() {
            $('#print_employee_ids > option').prop("selected", true);
            $('#print_employee_ids').trigger("change");
        }, 100);

        // Datepicker khusus modal
        $('#print_month_year').datepicker({
            autoclose: true,
            format: 'mm/yyyy',
            minViewMode: "months"
        });
    });

    // Select all employees (tombol select all)
    $('#print_all_payroll_modal').on('click', '.select-all', function (e) {
        e.preventDefault();
        $('#print_employee_ids > option').prop("selected", true);
        $('#print_employee_ids').trigger("change");
    });

    // Deselect all employees (tombol deselect all)
    $('#print_all_payroll_modal').on('click', '.deselect-all', function (e) {
        e.preventDefault();
        $('#print_employee_ids > option').prop("selected", false);
        $('#print_employee_ids').trigger("change");
    });

    // Validasi dan form submission
    $('#print_payroll_form').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var $btn = $('#print_payroll_btn');
        var selectedEmployees = $('#print_employee_ids').val();

        // Validasi: pastikan minimal ada 1 employee yang dipilih
        if (!selectedEmployees || selectedEmployees.length === 0) {
            if (typeof toastr !== 'undefined') {
                toastr.error('Please select at least one employee');
            } else {
                alert('Please select at least one employee');
            }
            return false;
        }

        $btn.prop('disabled', true).text('Loading...');

        var url = $form.attr('action') + '?' + $form.serialize();
        window.open(url, '_blank');

        setTimeout(function () {
            $btn.prop('disabled', false).text('@lang("messages.print")');
        }, 1000);
    });

    // Reset tombol saat modal ditutup
    $('#print_all_payroll_modal').on('hidden.bs.modal', function () {
        $('#print_payroll_btn').prop('disabled', false).text('@lang("messages.print")');
        
        // Reset form fields
        $('#print_employee_ids').val(null).trigger('change');
        $('#print_department_id').val(null).trigger('change');
        $('#print_designation_id').val(null).trigger('change');
        $('#print_status').val(null).trigger('change');
        $('#print_month_year').val('');
    });

    // Update visual feedback ketika employee selection berubah
    $('#print_employee_ids').on('change', function() {
        var selectedCount = $(this).val() ? $(this).val().length : 0;
        var totalCount = $(this).find('option').length;
        
        // Optional: Log untuk debugging
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
