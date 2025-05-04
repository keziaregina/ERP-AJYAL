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
    $shortname = !empty($shortname) ? $shortname : '';

@endphp
<tr>
    <td>
        <input type="text" name="{{ $name_col }}[]" value="{{ !empty($name) ? $name : '' }}" class="form-control input-sm">
    </td>
    <td>
        <select name="{{ $type_col }}[]" class="form-control input-sm amount_type">
            <option value="fixed" {{ $amount_type == 'fixed' ? 'selected' : '' }}>{{ __('lang_v1.fixed') }}</option>
            <option value="percent" {{ $amount_type == 'percent' ? 'selected' : '' }}>{{ __('lang_v1.percentage') }}</option>
        </select>
        <div class="input-group percent_field @if($amount_type != 'percent') hide @endif">
            <input type="text" name="{{ $percent_col }}[]" value="{{ @num_format($percent) }}" class="form-control input-sm input_number percent" readonly>
            <span class="input-group-addon"><i class="fa fa-percent"></i></span>
        </div>
    </td>
    
    @if ($type == 'allowance' && $shortname == 'overtime')
    <td>
        @php
            $readonly = $amount_type == 'percent' ? 'readonly' : '';
        @endphp
        <input type="text" name="payrolls[{{ $employee }}][overtime_hours][]" value="{{ $overtime_value }}" class="form-control input-sm value_field input_number overtime_hours" {{ $readonly }}>
    </td>
    @elseif ($type == 'allowance' && $shortname != 'overtime')
        <td></td>
    @endif

    {{-- @if ($type == 'deduction' && $shortname == 'social_security')
    <td>
        @php
            $readonly = $amount_type == 'percent' ? 'readonly' : '';
        @endphp
        <input type="text" name="payrolls[{{ $employee }}][social_security_amount][]" value="{{ $overtime_value }}" class="form-control input-sm value_field input_number overtime_hours" {{ $readonly }}>
    </td>
    @elseif ($type == 'allowance' && $shortname != 'overtime')
        <td></td>
    @endif --}}
    {{-- @dump($name) --}}

    <td>
        @php
            $readonly = $amount_type == 'percent' ? 'readonly' : '';
        @endphp
        <input type="text" name="{{ $val_col }}[]" value="{{ !empty($value) ? @num_format((float) $value) : 0 }}" class="form-control input-sm value_field input_number {{ $val_class }}" {{ $readonly }}>
    </td>
    <td>
        @if(!empty($add_button))
            <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-primary @if($type == 'allowance') add_allowance @elseif($type == 'deduction') add_deduction @endif">
                <i class="fa fa-plus"></i>
            </button>
        @else
            <button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-error remove_tr">
                <i class="fa fa-minus"></i>
            </button>
        @endif
    </td>
</tr>