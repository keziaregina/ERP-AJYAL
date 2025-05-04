<script type="text/javascript">
    $(document).ready( function () {
        // Function to get query parameters from URL
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }
    
        // Get month from URL query parameter
        // const monthFromUrl = getQueryParam('month');
        // window.url = monthFromUrl || new Date().toISOString().slice(0, 7); // Default to current month if not provided
    
        //add allowance row
        $('.add_allowance').click( function() {
            let id = $(this).parent().parent().parent().parent().data('id');
            $this = $(this);
            $.ajax({
                method: "GET",
                dataType: "html",
                data:{
                    'employee_id': id,
                    'type': 'allowance'
                },
                url: '/hrm/get-allowance-deduction-row',
                success: function(result){
                    $this.closest('.allowance_table tbody').append(result);
                }
            });
        });
    
        //add deduction row
        $('.add_deduction').click( function() {
            let id = $(this).parent().parent().parent().parent().data('id');
            $this = $(this);
            $.ajax({
                method: "GET",
                dataType: "html",
                data:{
                    'employee_id': id,
                    'type': 'deduction'
                },
                url: '/hrm/get-allowance-deduction-row',
                success: function(result){
                    $this.closest('.deductions_table tbody').append(result);
                }
            });
        });
    
        //remove allowance/deduction row
        $(document).on('click', 'button.remove_tr', function(){
            let id = $(this).parent().parent().parent().parent().data('id');
            $(this).closest('tr').remove();
            calculateTotal(id);
            calculateTotalGrossAmount();
        });
    
        //toggle allowance/deduction amount type
        $(document).on('change', '.amount_type', function(){
            let tr = $(this).closest('tr');
            if ($(this).val() == 'percent') {
                tr.find('.percent_field').removeClass('hide');
                tr.find('.value_field').attr('readonly', true);
            } else {
                tr.find('.percent_field').addClass('hide');
                tr.find('.value_field').removeAttr('readonly');
            }
        });
    
        //calculate amount per unit duration
        $(document).on('change', '.total', function() {
            let total_duration = __read_number($(this).closest('td').find('input.essentials_duration'));
            let total = __read_number($(this));
            let amount_per_unit_duration = total / total_duration;
            __write_number($(this).closest('td').find('input.essentials_amount_per_unit_duration'), amount_per_unit_duration, false, 2);
            calculateTotal($(this).data('id'));
            calculateTotalGrossAmount();
        });
    
        $(document).on('change', '.essentials_duration, .essentials_amount_per_unit_duration, input.allowance, input.deduction, input.percent', function() {
            let id = $(this).data('id');
            if ($(this).hasClass('allowance') || $(this).hasClass('deduction')) {
                id = $(this).parent().parent().parent().parent().data('id');
            } else if ($(this).hasClass('percent')) {
                console.log();
                id = $(this).parent().parent().parent().parent().parent().data('id');
            }
            calculateTotal(id);
            calculateTotalGrossAmount();
        });
    
        // On overtime hours change
        $(document).on('change', '.overtime_hours', function() {
            let id = $(this).parent().parent().parent().parent().data('id');
            let hours = __read_number($(this));
            let $this = $(this); // Store reference to use inside AJAX
    
            $.ajax({
                method: "GET",
                url: '/hrm/get-attendance-data',
                data: {
                    'user_id': id,
                    'month': $("input[name='transaction_date']").val()
                },
                success: function(result) {
                    let overtime_fee = result.overtime_fee || 0;
                    let decimal_breakpoint = result.decimal_breakpoint || 3;
                    
                    // Calculate overtime pay (hours * overtime_fee)
                    let overtimePay = hours * overtime_fee;
                    console.log('Overtime Fee:', overtime_fee);
                    console.log('Hours:', hours);
                    console.log('Overtime Pay:', overtimePay);
                    
                    // Update the allowance amount
                    __write_number($this.closest('tr').find('input.allowance'), overtimePay.toFixed(decimal_breakpoint), false, 2);
                    
                    // Update the total allowance
                    // let total_allowance = __read_number($this.closest('tr').find('input.allowance'));
                    // __write_number($this.closest('tr').find('input.allowance'), total_allowance + overtimePay.toFixed(decimal_breakpoint), false, 2);
    
                    // Recalculate totals
                    calculateTotal(id);
                    calculateTotalAllowances(id);
                    calculateTotalGrossAmount();
                }
            });
        });
    
        function calculateTotal(id) {
            try {
                //calculate basic salary
                let total_duration = __read_number($("input#essentials_duration_"+id));
                let amount_per_unit_duration = __read_number($("input#essentials_amount_per_unit_duration_"+id));
                let total = total_duration * amount_per_unit_duration;
                __write_number($("input#total_"+id), total, false, 2);
    
                // Get food allowance if exists
                let food_allowance = 0;
                $("table#allowance_table_"+id).find('tbody tr').each(function () {
                    let description = $(this).find('input[name*="[description]"]').val();
                    if (description && description.toLowerCase().includes('food')) {
                        food_allowance = __read_number($(this).find('input.allowance'));
                    }
                });
    
                // Calculate absent days deduction
                let absent_days = 0;
                let vacation_days = 0;
                let sick_leave_days = 0;
                let glorious_employee = false;
    
                let allowance_deduction_data = [];
    
                $.ajax({
                    method: "GET",
                    url: '/hrm/get-user-allow-deduct',
                    data: {
                        'user_id': id,
                        // 'month': $("input[name='transaction_date']").val()
                        'month': getQueryParam('month_year')
                    },
                    success: function(result) {
                        // console.log("result");
                        // console.log(result);
                        // console.log("result------------>");
                        // allowance_deduction_data = result;
                        allowances = result.allowances;
                        deductions = result.deductions;
                    }
                })
    
                // TODO: this one 
                // Get attendance data from overtime sheet
                $.ajax({
                    method: "GET",
                    url: '/hrm/get-attendance-data',
                    data: {
                        'user_id': id,
                        'month': $("input[name='transaction_date']").val()
                    },
                    success: function(result) {
                        absent_days = result.absent_days || 0;
                        vacation_days = result.vacation_days || 0;
                        sick_leave_days = result.sick_leave_days || 0;
                        glorious_employee = result.glorious_employee || false;
                        overtime_hours = result.overtime_hours || 0;
                        decimal_breakpoint = result.decimal_breakpoint || 3;
                        overtime_fee = result.overtime_fee || 0;
                        isGloriousEmployee = result.glorious_employee;
                        ge_amount = result.ge_amount || 0;
    
                        // Calculate absent deduction
                        let daily_rate = total / 30;
                        let absent_deduction = daily_rate * absent_days;
    
                        
                        // Calculate vacation deduction
                        let vacation_deduction = 0;
                        if (vacation_days > 0) {
                            let daily_food_allowance = food_allowance / 30;
                            vacation_deduction = (daily_rate + daily_food_allowance) * vacation_days;
                        }
    
                        // Add glorious employee allowance if applicable
                        let glorious_allowance = 0;
                        // if (isGloriousEmployee) {
                        //     glorious_allowance = ge_amount; // 10% of basic salary
                        // }
    
                        // Clear the allowance table
                        $("table#allowance_table_"+id+" tbody").empty();
                        $("table#deductions_table_"+id+" tbody").empty();
    
                        // foreach the allowance_deduction_data
                        $.each(allowances, function(key, value) {
                            if (value.name.includes('Glorious employee allowance')) {
                                if (isGloriousEmployee) {
                                    addAllowanceRow(id, value.amount_type, value.name, parseFloat(value.amount).toFixed(decimal_breakpoint));
                                }
                            } else {
                                addAllowanceRow(id, value.amount_type, value.name, parseFloat(value.amount).toFixed(decimal_breakpoint));
                            }
                        });
    
                        // Add sick leave allowance if applicable
                        let sick_leave_allowance = 0;
                        // if (sick_leave_days > 0) {
                        //     sick_leave_allowance = daily_rate * sick_leave_days;
                        // }
    
                        // Calculate overtime earnings
                        let overtime_earnings = 0;
                        if (overtime_hours > 0) {
                            overtime_earnings = overtime_fee * overtime_hours;
                        }
    
                        // Update the example calculations in the formulas section
                        updateFormulaExamples(id, daily_rate, absent_days, vacation_days, food_allowance, total, sick_leave_days);
    
                        // Clear the deductions table
    
                        // Add deductions to the deductions table
                        $.each(deductions, function(key, value) {
                            if (value.name.includes('Social Security Deductions')) {
                                amount = total * parseFloat(value.amount) / 100;
                                addDeductionForSocialSecurity(id, value.amount_type, value.name, parseFloat(value.amount).toFixed(decimal_breakpoint) + ' %', parseFloat(amount).toFixed(decimal_breakpoint));
                            } else {
                                addDeductionRow(id, value.amount_type, value.name, parseFloat(value.amount).toFixed(decimal_breakpoint));
                            }
                        });
    
                        if (absent_deduction > 0) {
                            addDeductionRow(id, 'Fixed', 'Absent Days Deduction', absent_deduction.toFixed(decimal_breakpoint));
                        } else {
                            addDeductionRow(id, 'Fixed', 'Absent Days Deduction', 0);
                        }
    
                        if (vacation_deduction > 0) {
                            addDeductionRow(id, 'Fixed', 'Vacation Leave Deduction', vacation_deduction.toFixed(decimal_breakpoint));
                        } else {
                            addDeductionRow(id, 'Fixed', 'Vacation Leave Deduction', 0);
                        }
    
                        // Add allowances to the allowance table
                        // if (glorious_allowance > 0) {
                        //     console.log("glorious allowance here");
                        //     console.log(glorious_allowance);
                        //     addAllowanceRow(id, 'Glorious employee allowance (GE) الموظف المجيد', glorious_allowance);
                        // }
    
                        if (overtime_earnings > 0) {
                            addAllowanceForOvertime(id, 'ساعات عمل إضافية Overtime', overtime_hours, overtime_earnings.toFixed(decimal_breakpoint));
                        }
    
                        // Recalculate totals
                        calculateTotalAllowances(id);
                        calculateTotalDeductions(id);
                        calculateGrossAmount(id);
                    }
                });
            } catch (error) {
                console.error('Error in calculateTotal:', error);
            }
        }
    
        function updateFormulaExamples(id, daily_rate, absent_days, vacation_days, food_allowance, total, sick_leave_days) {
            // Update absent calculation example
            let absent_example = daily_rate * absent_days;
            $('#absent_calculation_example').text(__currency_trans_from_en(absent_example, true));
            
            // Update vacation calculation example
            let daily_food_allowance = food_allowance / 30;
            let vacation_example = (daily_rate + daily_food_allowance) * vacation_days;
            $('#vacation_calculation_example').text(__currency_trans_from_en(vacation_example, true));
            
            // Update glorious employee example
            let glorious_example = total * 0.1;
            $('#glorious_employee_example').text(__currency_trans_from_en(glorious_example, true));
            
            // Update sick leave example
            let sick_leave_example = daily_rate * sick_leave_days;
            $('#sick_leave_example').text(__currency_trans_from_en(sick_leave_example, true));
        }
    
        function addDeductionRow(id, type = 'Fixed', description, amount) {
            try {
                let row = `
                    <tr>
                        <td>
                            <input type="text" name="payrolls[${id}][deductions][description][]" value="${description}" class="form-control" readonly>
                        </td>
                        <td>
                            <input type="text" name="payrolls[${id}][deductions][amount_type][]" value="${type}" class="form-control" readonly>
                            <input type="text" name="payrolls[${id}][deductions][percentage][]" value="0" class="form-control hidden" readonly>
                        </td>
                        <td>
                            <input type="text" name="payrolls[${id}][deductions][amount][]" value="${amount}" class="form-control input_number deduction" readonly>
                        </td>
                        <td></td>
                    </tr>
                `;
                // // Clear all fields
                // $("table#deductions_table_"+id+" tbody").empty();
    
                // Append the new row
                $("table#deductions_table_"+id+" tbody").append(row);
            } catch (error) {
                console.error('Error in addDeductionRow:', error);
            }
        }
    
        function addDeductionForSocialSecurity(id, type = 'Fixed', description, percentage, amount) {
            try {
                let row = `
                    <tr>
                        <td>
                            <input type="text" name="payrolls[${id}][deductions][description][]" value="${description}" class="form-control" readonly>
                        </td>
                        <td>
                            <input type="text" name="payrolls[${id}][deductions][amount_type][]" value="${type}" class="form-control" readonly>
                            <input type="text" name="payrolls[${id}][deductions][percentage][]" value="${percentage}" class="form-control" readonly>
                        </td>
                        <td>
                            <input type="text" name="payrolls[${id}][deductions][amount][]" value="${amount}" class="form-control input_number deduction" readonly>
                        </td>
                        <td></td>
                    </tr>
                `;
              
                $("table#deductions_table_"+id+" tbody").append(row);
            } catch (error) {
                console.error('Error in addDeductionRow:', error);
            }
        }
    
        function addAllowanceRow(id, type = 'Fixed', description, amount) {
            try {
                let row = `
                    <tr>
                        <td>
                            <input type="text" name="payrolls[${id}][allowances][description][]" value="${description}" class="form-control" readonly>
                        </td>
                        <td>
                            <input type="text" name="payrolls[${id}][allowances][amount_type][]" value="${type}" class="form-control" readonly>
                        </td>
                        <td>
                            <input type="text"  value="-" class="form-control input_number " readonly>
                        </td>
                        <td>
                            <input type="text" name="payrolls[${id}][allowances][amount][]" value="${amount}" class="form-control input_number allowance" readonly>
                        </td>
                        <td></td>
                    </tr>
                `;
                // // Clear all fields
    
                // Append the new row
                $("table#allowance_table_"+id+" tbody").append(row);
            } catch (error) {
                console.error('Error in addAllowanceRow:', error);
            }
        }
    
        function addAllowanceForOvertime(id, description, overtime_hours, amount) {
            try {
                let row = `
                    <tr>
                        <td>
                            <input type="text" name="payrolls[${id}][allowances][description][]" value="${description}" class="form-control" readonly>
                        </td>
                        <td>
                            <input type="text" name="payrolls[${id}][allowances][amount_type][]" value="Fixed" class="form-control" readonly>
                        </td>
                        <td>
                            <input type="text" name="payrolls[${id}][allowances][overtime_hours][]" value="${overtime_hours}" class="form-control input_number overtime_hours" readonly>
                        </td>
                        <td>
                            <input type="text" name="payrolls[${id}][allowances][amount][]" value="${amount}" class="form-control input_number allowance" readonly>
                        </td>
                        <td></td>
                    </tr>
                `;
                $("table#allowance_table_"+id+" tbody").append(row);
            } catch (error) {
                console.error('Error in addAllowanceForOvertime:', error);
            }
        }
    
        function calculateTotalAllowances(id) {
            let total_allowance = 0;
            $("table#allowance_table_"+id).find('tbody tr').each(function () {
                // console.log($(this).find('input.allowance'));
                total_allowance += __read_number($(this).find('input.allowance'));
            });
    
            console.log("TOTAL ALLOWANCE");
            console.log(total_allowance);
            $('#total_allowances_'+id).text(__currency_trans_from_en(total_allowance, true));
            return total_allowance;
        }
    
        function calculateTotalDeductions(id) {
            let total_deduction = 0;
            $("table#deductions_table_"+id).find('tbody tr').each(function () {
                total_deduction += __read_number($(this).find('input.deduction'));
            });
            $('#total_deductions_'+id).text(__currency_trans_from_en(total_deduction, true));
    
            console.log("TOTAL DEDUCTION");
            console.log(total_deduction);
    
            return total_deduction;
        }
    
        function calculateGrossAmount(id) {
            let basic_salary = __read_number($("input#total_"+id));
            let total_allowances = calculateTotalAllowances(id);
            let total_deductions = calculateTotalDeductions(id);
            
            // console.log("BASIC SALARY");
            // console.log(basic_salary);
            // console.log("TOTAL ALLOWANCES");
            // console.log(total_allowances);
            // console.log("TOTAL DEDUCTIONS");
            // console.log(total_deductions);
    
            let gross_amount = basic_salary + total_allowances - total_deductions;
    
            console.log("GROSS AMOUNT");    
            console.log(gross_amount);
            
            // Update the gross amount display and hidden input
            $('#gross_amount_text_'+id).text(__currency_trans_from_en(gross_amount, true));
            __write_number($('#gross_amount_'+id), gross_amount);
            
            return gross_amount;
        }
    
        function calculateTotalGrossAmount() {
            try {
                let total_gross = 0;
                $('input.gross_amount').each(function() {
                    total_gross += __read_number($(this)) || 0;
                });
                __write_number($('#total_gross_amount'), total_gross, false, 2);
            } catch (error) {
                console.error('Error in calculateTotalGrossAmount:', error);
            }
        }
    
        // Initialize calculations for all employees
        $("#payroll_table tr[data-id]").each(function() {
            let id = $(this).data('id');
            if (id) {
                calculateTotal(id);
            }
        });
    
        // Recalculate on input changes
        $(document).on('change', '.essentials_duration, .essentials_amount_per_unit_duration, .allowance, .deduction', function() {
            let id = $(this).closest('tr').data('id');
            if (id) {
                calculateTotal(id);
            }
        });
    });
    </script>