<table>
    <tr>
        <th>Employer CR-NO</th>
        <th>Payer CR-NO</th>
        <th>Payer Bank Short Name</th>
        <th>Payer Account  Number</th>
        <th>Salary Year</th>
        <th>Salary Month</th>
        <th>Total Salaries</th>
        <th>Number Of Records</th>
        <th>Payment Type</th>
    </tr>
    <tr>
        <td>{{ $companyBankDetail->employer_cr_no }}</td>
        <td>{{ $companyBankDetail->payer_cr_no }}</td>
        <td>{{ $companyBankDetail->payer_bank_short_name }}</td>
        <td>{{ $companyBankDetail->payer_account_number }}</td>
        <td>{{ $salaryYear }}</td>
        <td>{{ $salaryMonth }}</td>
        <td>{{ number_format($totalSalary, 3, '.', ',') }}</td>
        <td>{{ $numberOfRecords }}</td>
        <td>Salary</td>
    </tr>
    <tr>
        <th>Employee ID Type</th>
        <th>Employee ID</th>
        <th>Reference Number</th>
        <th>Employee Name</th>
        <th>Employee BIC Code</th>
        <th>Employee Account</th>
        <th>Salary Frequency</th>
        <th>Number Of Working days</th>
        <th>Net Salary</th>
        <th>Basic Salary</th>
        <th>Extra Hours</th>
        <th>Extra Income</th>
        <th>Deductions</th>
        <th>Social Security Deductions</th>
        <th>Notes / Comments</th>
    </tr>
    @foreach ($transactionPayrolls as $transactionPayroll)
        <tr>
            <td>{{ $companyBankDetail->employee_type_id }}</td>
            @if ($companyBankDetail->employee_type_id == 'C')
                <td>{{ $transactionPayroll->transaction_for->custom_field_4 }}</td>
            @else
                <td>{{ $transactionPayroll->transaction_for->custom_field_2 }}</td>
            @endif
            <td>{{ $loop->index + 1 }}</td>
            <td>{{ $transactionPayroll->transaction_for->first_name }}</td>
            <td>{{ $transactionPayroll->transaction_for?->employeeBankCode?->name ?? '-' }}</td>
            @php
                $bankDetails = json_decode($transactionPayroll->transaction_for->bank_details);
            @endphp
            <td>{{ $bankDetails->{'account_number'} }}</td>
            <td>{{ $transactionPayroll->transaction_for?->employeeSalaryFrequency?->name ?? '-' }}</td>
            <td>{{ $transactionPayroll->working_days }}</td>
            <td>{{ number_format($transactionPayroll->final_total, 3, '.', '') }}</td>
            <td>{{ number_format($transactionPayroll->total_before_tax, 3, '.', '') }}</td>
            <td>{{ $transactionPayroll->extra_hours }}</td>
            <td>{{ number_format($transactionPayroll->essentials_allowances, 3, '.', '') }}</td>
            <td>{{ number_format($transactionPayroll->essentials_deductions, 3, '.', '') }}</td>
            <td>{{ number_format($transactionPayroll->social_security_deductions, 3, '.', '') }}</td>
            <td></td>
        </tr>
    @endforeach
</table>