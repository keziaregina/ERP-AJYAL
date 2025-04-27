<table>
    <thead>
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
            <td>{{ date('Y') }}</td>
            <td>{{ date('m') }}</td>
            <td>{{ $totalSalary }}</td>
            <td>{{ $numberOfRecords }}</td>
            <td>Salary</td>
        </tr>
    </thead>
</table>

<table>
    <thead>
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
    </thead>
    <tbody>
        @foreach ($transactionPayrolls as $transactionPayroll)
            <tr>
                <td>{{ $companyBankDetail->employee_id_type }}</td>
                <td>{{ $companyBankDetail->employee_id_type }}</td>
                {{-- <td>{{ $companyBankDetail->employee_id }}</td>
                <td>{{ $transactionPayroll['ref_no'] }}</td>
                <td>{{ $transactionPayroll['employee_name'] }}</td>
                <td>{{ $transactionPayroll['employee_bic_code'] }}</td>
                <td>{{ $transactionPayroll['employee_account'] }}</td> --}}
                
            </tr>
        @endforeach
    </tbody>
</table>