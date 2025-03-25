<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tax Report</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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

        .report-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .date{
            font-size: 11px;
        }
        .indexing {
            width: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        th,
        td {
            border: 0.5px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #2C3E50;
            color: white;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .box {
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f8fafc;
            padding: 20px;
        }
        .label {
            font-weight: bold;
            font-size: 12px
        }

        .title {
            font-size: 13px;
            font-weight: bold;
        }

        .value{
            font-size: 11px;
        }

        .total {
            background-color: #c8c9ca;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <img class="logo" src="{{ $logo }}" alt="logo">
        <h1>Ajyal Al - Madina Al - Asria</h1>
        <span>{{ env('APP_TITLE') }}</span>

        {{ Log::info('CUSTOMER & SUPPLIER -------------------------------------------------->') }}
        {{ Log::info(json_encode($report, JSON_PRETTY_PRINT)) }}
    </div>

    <div class="report-title">
        Taxes Report
    </div>

    <p class="date">
        Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
    </p>

    <div class="box">
        <div class="label">
            Overall (Input - Output - Expense)
        </div>
        <div class="value">
            Output Tax - Input Tax - Expense Tax : {{ number_format($report['tax_diff'], 3) }} SAR
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 class="title" style="margin-bottom: 15px">Input Tax (Purchase)</h3>

        <table>
            <thead>
                <tr>
                    <th class="indexing">#</th>
                    <th>Date</th>
                    <th>Reference No.</th>
                    <th>Supplier</th>
                    <th>Tax Number</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Discount</th>
                    @foreach ($report['taxes'] as $tax)
                        <th>{{ $tax['name'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if (count($report['input_tax_details']['tax_details']) > 0)
                    @foreach ($report['input_tax_details']['tax_details'] as $index => $item)
                        <tr>
                            <td class="indexing">{{ $index + 1 }}</td>
                            <td>{{ $item->transaction_date ?: '-' }}</td>
                            <td>{{ $item->ref_no ?: '-' }}</td>
                            <td>{{ $item->contact_name ?: '-' }}</td>
                            <td>{{ $item->tax_number ?: '-' }}</td>
                            <td>{{ number_format($item->total_before_tax, 3) ?: '0' }} {{ $currency }}</td>
                            <td>{{ $item->payment_methods ?: '-' }}</td>
                            <td>{{ number_format($item->discount_amount, 3) ?: '0' }} {{ $currency }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11">No data available in table</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 class="title" style="margin-bottom: 15px">Output Tax (Sales)</h3>

        <table>
            <thead>
                <tr>
                    <th class="indexing">#</th>
                    <th>Date</th>
                    <th>Invoice No.</th>
                    <th>Customer</th>
                    <th>Tax Number</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Discount</th>
                    @foreach ($report['taxes'] as $tax)
                        <th>{{ $tax['name'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if (count($report['output_tax_details']['tax_details']) > 0)
                    @foreach ($report['output_tax_details']['tax_details'] as $index => $item)
                        <tr class="indexing">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->transaction_date ?: '-' }}</td>
                            <td>{{ $item->invoice_no ?: '-' }}</td>
                            <td>{{ $item->contact_name ?: '-' }}</td>
                            <td>{{ $item->tax_number ?: '-' }}</td>
                            <td>{{ number_format($item->total_before_tax, 3) ?: '0' }} {{ $currency }}</td>
                            <td>{{ $item->payment_methods ?: '-' }}</td>
                            <td>{{ number_format($item->discount_amount, 3) ?: '0' }} {{ $currency }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11">No data available in table</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 class="title" style="margin-bottom: 15px">Expense Tax</h3>

        <table>
            <thead>
                <tr>
                    <th class="indexing">#</th>
                    <th>Date</th>
                    <th>Reference No.</th>
                    <th>Tax Number</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Discount</th>
                    @foreach ($report['taxes'] as $tax)
                        <th>{{ $tax['name'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if (count($report['expense_tax_details']['tax_details']) > 0)
                    @foreach ($report['expense_tax_details']['tax_details'] as $index => $item)
                        <tr>
                            <td class="indexing">{{ $index + 1 }}</td>
                            <td>{{ $item->transaction_date ?: '-' }}</td>
                            <td>{{ $item->ref_no ?: '-' }}</td>
                            <td>{{ $item->tax_number ?: '-' }}</td>
                            <td>{{ number_format($item->total_before_tax, 3) ?: '0' }} {{ $currency }}</td>
                            <td>{{ $item->payment_methods ?: '-' }}</td>
                            <td>{{ number_format($item->discount_amount, 3) ?: '0' }} {{ $currency }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="10">No data available in table</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>
