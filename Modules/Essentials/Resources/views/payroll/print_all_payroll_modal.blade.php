<div class="modal fade" id="print_all_payroll_modal" tabindex="-1" role="dialog" 
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
					{!! Form::select('employee_ids[]', $employees, null, ['class' => 'form-control select2', 'required', 'style' => 'width: 100%;', 'multiple', 'id' => 'employee_ids']); !!}
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
                    {!! Form::label('month_year', __( 'essentials::lang.month_year' ) . ':') !!}
                    <div class="input-group">
                        {!! Form::text('month_year', null, ['class' => 'form-control', 'placeholder' => __( 'essentials::lang.month_year' ), 'id' => 'print_month_year', 'readonly' ]); !!}
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

