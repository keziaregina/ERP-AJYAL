<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payroll Report</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
            margin: 10px;
            padding: 10px;
        }

        /* Header Styles */
        .header-info {
            margin-bottom: 20px;
            width: 100%;
        }

        .header-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .header-table td {
            padding: 5px;
            text-align: left;
            font-size: 12px;
            background-color: #f8f9fa;
        }

        .header {
            text-align: center; 
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 12px;
            margin: 5px 10px 0px;
        }

        .logo {
            width: 70px; 
        }

        /* Main Table Styles */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
            font-size: 8px;
            margin: 0;
            padding: 0;
        }

        .main-table-wrapper {
            width: 100%;
            margin: 0;
            padding: 0;
            overflow-x: auto;
        }

        .main-table th,
        .main-table td {
            border: 0.5px solid #dee2e6;
            padding: 3px;
            text-align: center;
            vertical-align: middle;
            min-width: 40px;
        }

        .main-table th {
            background-color: #e9ecef;
            font-weight: bold;
            color: #495057;
            font-size: 8px;
            white-space: normal;
            word-wrap: break-word;
            line-height: 1.1;
        }

        .main-table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .main-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .main-table tr:nth-child(odd) {
            background-color: #c5e6f2;
        }

        /* Amount Columns */
        .amount-column {
            background-color: #f8f9fa;
            font-family: 'DejaVu Sans Mono', monospace;
            text-align: right !important;
            padding-right: 4px !important;
            min-width: 60px !important;
        }

        .id-column {
            min-width: 70px;
        }

        .name-column {
            min-width: 100px;
            text-align: left !important;
        }

        .account-column {
            min-width: 90px;
        }

        .small-column {
            min-width: 40px;
        }

        /* Status Styles */
        .status-complete {
            color: #28a745;
            font-weight: bold;
        }

        /* Text Styles */
        .strong {
            font-weight: bold;
            color: #495057;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Utility Classes */
        .mb-10 {
            margin-bottom: 10px;
        }

        /* Header Section Background */
        .header-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="header">
        <img class="logo" src="{{ $logo }}" alt="logo">
        <h1>Ajyal Al - Madina Al - Asria</h1>        
    </div>
    <div class="header-section">
        <table class="header-table">
            <tr>
                <td width="50%"><span class="strong">PAYER COMPANY NAME:</span> {{ $company_name ?? config('app.name') }}</td>
                <td width="50%"><span class="strong">PAYER CR NUMBER:</span> {{ $companyBankDetail->employer_cr_no ?? '2' }}</td>
            </tr>
            <tr>
                <td><span class="strong">PAYER ACCOUNT NUMBER:</span> {{ $companyBankDetail->payer_account_number ?? '0475/01/012387/001' }}</td>
                <td><span class="strong">Net Salary:</span> {{ $totalSalary ?? '334,467' }}</td>
            </tr>
            <tr>
                <td><span class="strong">NO. OF RECORDS:</span> {{ $numberOfRecords ?? '13' }}</td>
                <td><span class="strong">PAYER BANK SHORT NAME:</span> {{ $companyBankDetail->payer_bank_short_name ?? 'BMCE' }}</td>
            </tr>
            <tr>
                <td><span class="strong">PAYMENT TYPE:</span> {{ 'Salary' }}</td>
                <td><span class="strong">MONTH:</span> {{  $monthName }} {{ $year }}</td>
            </tr>
            <tr>
                <td><span class="strong">PRINT DATE:</span> {{ $createPdfDate }}</td>
                <td><span class="strong">PRINT BY:</span> {{ $user }}</td>
            </tr>
        </table>
    </div>

    <div class="main-table-wrapper">
        <table class="main-table">
            <thead>
                <tr>
                    <th class="small-column">Ref</th>
                    <th class="small-column">ID Type</th>
                    <th class="id-column">ID No.</th>
                    <th class="name-column">Employee Name</th>
                    <th class="small-column">Bank</th>
                    <th class="account-column">Account No.</th>
                    <th class="small-column">Freq.</th>
                    <th class="small-column">Days</th>
                    <th class="amount-column">Extra Hrs</th>
                    <th class="amount-column">Basic Salary</th>
                    <th class="amount-column">Extra Income</th>
                    <th class="amount-column">Deductions</th>
                    <th class="amount-column">Social Security</th>
                    <th class="amount-column">Net Salary</th>
                    <th class="small-column">Notes</th>
                    <th class="small-column">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payrollData ?? [] as $row)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $companyBankDetail->employee_type_id ?? 'C' }}</td>
                    @if ($companyBankDetail->employee_type_id == 'C')
                        <td>{{ $row->transaction_for->custom_field_4 }}</td>
                    @else
                        <td>{{ $row->transaction_for->custom_field_2 }}</td>
                    @endif
                    <td class="name-column">{{ $row->transaction_for->first_name ?? '' }}</td>
                    <td>{{ $row->transaction_for?->employeeBankCode?->name ?? '-' }}</td>
                    @php
                        $bankDetails = json_decode($row->transaction_for->bank_details);
                    @endphp
                    <td class="account-column">{{ $bankDetails->{'account_number'} ?? '' }}</td>
                    <td>{{ $row->transaction_for?->employeeSalaryFrequency?->name ?? '-' }}</td>
                    <td>{{ $row->working_days ?? '-' }}</td>
                    <td class="">{{ $row->extra_hours ?? '-' }}</td>
                    <td class="">{{ number_format($row->total_before_tax, 3, '.', ',') }}</td>
                    <td class="">{{ number_format($row->essentials_allowances, 3, '.', ',') }}</td>
                    <td class="">{{ number_format($row->essentials_deductions, 3, '.', ',') }}</td>
                    <td class="">{{ number_format($row->social_security_deductions, 3, '.', ',') }}</td>
                    <td class="">{{ number_format($row->final_total, 3, '.', ',') }}</td>
                    <td>{{ $row->notes ?? '' }}</td>
                    <td class="status-complete">{{ $row->status ?? 'Complete' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 