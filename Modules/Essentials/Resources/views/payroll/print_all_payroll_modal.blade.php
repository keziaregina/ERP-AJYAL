<div class="modal fade" id="print_all_payroll_modal" tabindex="-1" aria-labelledby="printPayrollLabel" aria-hidden="true"" 
     aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            {!! Form::open(['url' => action([\Modules\Essentials\Http\Controllers\PayrollController::class, 'printPayroll']), 'method' => 'get', 'id' => 'print_payroll_form', 'target' => '_blank']) !!}

            <div class="modal-body">
                <div class="form-group">
					{!! Form::label('employee_ids', __( 'essentials::lang.employee' ) . ':') !!}
					<button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline  tw-dw-btn-primary select-all">
						@lang('lang_v1.select_all')
					</button>
					<button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline  tw-dw-btn-primary deselect-all">
						@lang('lang_v1.deselect_all')
					</button>
                    {!! Form::select('employee_ids[]', $employees, null, ['class' => 'form-control select2', 'style' => 'width: 100%;', 'multiple', 'id' => 'employee_ids']); !!}
				</div>

                <div class="form-group">
                    {!! Form::label('department_id', __('essentials::lang.department') . ':') !!}
                    {!! Form::select('department_id', $departments, null, ['class' => 'form-control select2', 'style' => 'width: 100%;', 'id' => 'print_department_id', 'placeholder' => __('messages.please_select')]); !!}
                </div>

                <div class="form-group">
                    {!! Form::label('designation_id', __('essentials::lang.designation') . ':') !!}
                    {!! Form::select('designation_id', $designations, null, ['class' => 'form-control select2', 'style' => 'width: 100%;', 'id' => 'print_designation_id', 'placeholder' => __('messages.please_select')]); !!}
                </div>

                <div class="form-group">
					{!! Form::label('month_year', __( 'essentials::lang.month_year' ) . ':*') !!}
					<div class="input-group">
						{!! Form::text('month_year', null, ['class' => 'form-control', 'placeholder' => __( 'essentials::lang.month_year' ), 'required', 'readonly' ]); !!}
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>

			
				<div class="form-group">
					{!! Form::label('status', __( 'essentials::lang.status' ) . ':') !!}
					{!! Form::select('status', ['paid' => __('lang_v1.paid'), 'due' => __('lang_v1.due'), 'partial' => __('lang_v1.partial')], null, ['class' => 'form-control select2', 'style' => 'width: 100%;', 'id' => 'print_status', 'placeholder' => __('messages.please_select')]); !!}
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


<script>
$(document).ready(function () {
    $(document).off('click', '#print_payroll_btn').on('click', '#print_payroll_btn', function (e) {
        e.preventDefault();
        
        let $btn = $(this);
        let $form = $('#print_payroll_form');
        
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> @lang('messages.print')');
        
        let monthYear = $('#month_year').val();
        if (!monthYear) {
            alert('@lang("messages.please_select") @lang("essentials::lang.month_year")');
            resetButton($btn);
            return;
        }

        let url = $form.attr('action');
        let params = $form.serialize();
        
        let printWindow = window.open(url + '?' + params, '_blank');
        
        if (printWindow) {
            setTimeout(function() {
                resetButton($btn);
            }, 2000);
        } else {
            alert('@lang("messages.allow_popups")');
            resetButton($btn);
        }
    });

    function resetButton($btn) {
        $btn.prop('disabled', false).html('@lang('messages.print')');
    }

    $('#print_all_payroll_modal').on('hidden.bs.modal', function () {
        $('#print_payroll_form')[0].reset();
        $('.select2').each(function() {
            $(this).val(null).trigger('change');
        });
        $('#print_payroll_btn').prop('disabled', false).html('@lang('messages.print')');
    });

    $(document).on('click', '.select-all', function() {
        let employeeIds = [];
        $('#employee_ids option').each(function() {
            employeeIds.push($(this).val());
        });
        $('#employee_ids').val(employeeIds).trigger('change');
    });

    $(document).on('click', '.deselect-all', function() {
        $('#employee_ids').val(null).trigger('change');
    });
});
</script>




