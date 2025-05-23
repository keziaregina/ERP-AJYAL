@php
    if($type == 'allowance') {
        $name_col = 'payrolls['.$employee.'][allowance_names]';
        $val_col = 'payrolls['.$employee.'][allowance_amounts]';
        $val_class = 'allowance';
        $type_col = 'payrolls['.$employee.'][allowance_types]';
        $percent_col = 'payrolls['.$employee.'][allowance_percent]';
    } elseif($type == 'deduction') {
        $name_col = 'payrolls['.$employee.'][deduction_names]';
        $val_col = 'payrolls['.$employee.'][deduction_amounts]';
        $val_class = 'deduction';
        $type_col = 'payrolls['.$employee.'][deduction_types]';
        $percent_col = 'payrolls['.$employee.'][deduction_percent]';
    }

    $amount_type = !empty($amount_type) ? $amount_type : 'fixed';
    $percent = $amount_type == 'percent' && !empty($percent) ?  $percent : 0;

@endphp
<tr>
    <td>
        {!! Form::text($name_col . '[]', !empty($name) ? $name : null, ['class' => 'form-control input-sm' ]); !!}
    </td>
    <td>
        {!! Form::select($type_col . '[]', ['fixed' => __('lang_v1.fixed'), 'percent' => __('lang_v1.percentage')], $amount_type, ['class' => 'form-control input-sm amount_type' ]); !!}
        {{-- {!! Form::select($type_col . '[]', ['fixed' => __('lang_v1.fixed'), 'percent' => __('lang_v1.percentage'), 'overtime' => __('lang_v1.overtime')], $amount_type, ['class' => 'form-control input-sm amount_type' ]); !!} --}}
        <div class="input-group percent_field @if($amount_type != 'percent') hide @endif">
            {!! Form::text($percent_col . '[]', @num_format($percent), ['class' => 'form-control input-sm input_number percent']); !!}
            <span class="input-group-addon"><i class="fa fa-percent"></i></span>
        </div>
    </td>
    
    @if ($type == 'allowance' && !empty($name) && str_contains($name, 'Overtime') )
    <td>
        @php
            $readonly = $amount_type == 'percent' ? 'readonly' : '';
        @endphp
        {!! Form::text('payrolls['.$employee.'][overtime_hours][]',  0, ['class' => 'form-control input-sm value_field input_number ' . 'overtime_hours', $readonly ]); !!}
    </td>
    @elseif ($type == 'allowance' && !empty($name) && !str_contains($name, 'Overtime') )
        <td>

        </td>
    @endif
    {{-- @dump($name) --}}

    <td>
        @php
            $readonly = $amount_type == 'percent' ? 'readonly' : '';
        @endphp
        {!! Form::text($val_col . '[]', !empty($value) ? @num_format((float) $value) : 0, ['class' => 'form-control input-sm value_field input_number ' . $val_class, $readonly ]); !!}
    </td>
    <td>
        @if(!empty($add_button))
            <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline  tw-dw-btn-primary @if($type == 'allowance') add_allowance @elseif($type == 'deduction') add_deduction @endif">
            <i class="fa fa-plus"></i>
        @else
            <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline  tw-dw-btn-error remove_tr"><i class="fa fa-minus"></i></button>
        @endif
    </button></td>
</tr>