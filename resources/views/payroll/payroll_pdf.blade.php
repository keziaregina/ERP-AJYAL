<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payroll Report</title>
    <style>
        /* Reset CSS */
        * {
            margin: 10px;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
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

        /* Main Table Styles */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            white-space: nowrap;
            font-size: 9px;
        }

        .main-table th,
        .main-table td {
            border: 0.5px solid #dee2e6;
            padding: 4px 2px;
            text-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .main-table th {
            background-color: #e9ecef;
            font-weight: bold;
            color: #495057;
            font-size: 9px;
            white-space: normal;
            word-wrap: break-word;
        }

        .main-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .main-table tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* Hover effect for rows */
        .main-table tr:hover {
            background-color: #f2f2f2;
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

        /* Amount Columns */
        .amount-column {
            background-color: #f8f9fa;
            font-family: 'DejaVu Sans Mono', monospace;
            text-align: right !important;
            padding-right: 4px !important;
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
    <div class="header-section">
        <table class="header-table">
            <tr>
                <td width="50%"><span class="strong">PAYER COMPANY NAME:</span> {{ $company_name ?? 'AYAL ALMACEN GLASSCCO ASS' }}</td>
                <td width="50%"><span class="strong">PAYER CR NUMBER:</span> {{ $companyBankDetail->employer_cr_no ?? '1068122' }}</td>
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
                <td colspan="2"><span class="strong">PAYMENT TYPE:</span> {{ 'Salary' }}</td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 3%">Ref</th>
                <th style="width: 3%">ID Type</th>
                <th style="width: 7%">ID No.</th>
                <th style="width: 12%">Name</th>
                <th style="width: 6%">Bank</th>
                <th style="width: 8%">Account No.</th>
                <th style="width: 4%">Freq.</th>
                <th style="width: 4%">Days</th>
                <th style="width: 6%" class="amount-column">Extra Hrs</th>
                <th style="width: 8%" class="amount-column">Basic Salary</th>
                <th style="width: 8%" class="amount-column">Extra Income</th>
                <th style="width: 8%" class="amount-column">Deductions</th>
                <th style="width: 8%" class="amount-column">Social Security</th>
                <th style="width: 8%" class="amount-column">Net</th>
                <th style="width: 4%">Notes</th>
                <th style="width: 5%">Status</th>
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
                <td style="text-align: left">{{ $row->transaction_for->first_name ?? '' }}</td>
                <td>{{ $row->transaction_for?->employeeBankCode?->name ?? '-' }}</td>
                @php
                    $bankDetails = json_decode($row->transaction_for->bank_details);
                @endphp
                <td>{{ $bankDetails->{'account_number'} ?? '' }}</td>
                <td>{{ $row->transaction_for?->employeeSalaryFrequency?->name ?? '-' }}</td>
                <td>{{ $row->working_days ?? '-' }}</td>
                <td class="amount-column">{{ $row->extra_hours ?? '-' }}</td>
                <td class="amount-column">{{ number_format($row->total_before_tax, 3, '.', ',') }}</td>
                <td class="amount-column">{{ number_format($row->essentials_allowances, 3, '.', ',') }}</td>
                <td class="amount-column">{{ number_format($row->essentials_deductions, 3, '.', ',') }}</td>
                <td class="amount-column">{{ number_format($row->social_security_deductions, 3, '.', ',') }}</td>
                <td class="amount-column">{{ number_format($row->final_total, 3, '.', ',') }}</td>
                <td>{{ $row->notes ?? '' }}</td>
                <td class="status-complete">{{ $row->status ?? 'Complete' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 