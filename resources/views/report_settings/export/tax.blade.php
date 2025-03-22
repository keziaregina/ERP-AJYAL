<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tax Report</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: left;
        }
        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .header-box{
            margin-left: 20px;
        }

        .box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: #f8fafc;
        }

        .label {
            margin-bottom: 10px;
            font-weight: bold;
            color: #6b7280;
            font-size: 15px;
        }

        .value {
            font-size: 20px;
            color: #1e293b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #2C3E50;
            color: white;
            font-weight: bold;
        }
        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .logo {
            width: 100px;
            height: 100px;
        }

        .center {
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <img class="logo" src="{{ $logo }}" alt="logo">
        <h3>Ajyal Al - Madina</h3>

        {{ Log::info("CUSTOMER & SUPPLIER -------------------------------------------------->") }}
        {{ Log::info(json_encode($report,JSON_PRETTY_PRINT)) }}
    </header>
    <main>
        <div class="report-title">
            Tax Report - AJYAL AL-MADINA AL ASRIA
        </div>
        <div class="container">
            <p>
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
                <h3 style="margin-bottom: 15px">Input Tax (Purchase)</h3>

                <table border="0">
                    <thead>
                        <th>#</th>
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
                    </thead>
                    <tbody>
                        @if (count($report['input_tax_details']['tax_details']) > 0)
                            @foreach ($report['input_tax_details']['tax_details'] as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->transaction_date }}</td>
                                <td>{{ $item->ref_no }}</td>
                                <td>{{ $item->contact_name }}</td>
                                <td>{{ $item->tax_number }}</td>
                                <td>{{ $item->total_before_tax }}</td>
                                <td>{{ $item->payment_methods }}</td>
                                <td>{{ $item->discount_amount }}</td>
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

            <div style="margin-bottom: 20px;">
                <h3 style="margin-bottom: 15px">Output Tax (Sales)</h3>

                <table border="0">
                    <thead>
                        <th>#</th>
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
                    </thead>
                    <tbody>
                        @if (count($report['output_tax_details']['tax_details']) > 0)
                            @foreach ($report['output_tax_details']['tax_details'] as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->transaction_date }}</td>
                                <td>{{ $item->invoice_no }}</td>
                                <td>{{ $item->contact_name }}</td>
                                <td>{{ $item->tax_number }}</td>
                                <td>{{ $item->total_before_tax }}</td>
                                <td>{{ $item->payment_methods }}</td>
                                <td>{{ $item->discount_amount }}</td>
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

            <div style="margin-bottom: 20px;">
                <h3 style="margin-bottom: 15px">Expense Tax</h3>

                <table border="0">
                    <thead>
                        <th>#</th>
                        <th>Date</th>
                        <th>Reference No.</th>
                        <th>Tax Number</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Discount</th>
                        @foreach ($report['taxes'] as $tax)
                            <th>{{ $tax['name'] }}</th>
                        @endforeach
                    </thead>
                    <tbody>
                        @if (count($report['expense_tax_details']['tax_details']) > 0)
                            @foreach ($report['expense_tax_details']['tax_details'] as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->transaction_date }}</td>
                                <td>{{ $item->ref_no }}</td>
                                <td>{{ $item->tax_number }}</td>
                                <td>{{ $item->total_before_tax }}</td>
                                <td>{{ $item->payment_methods }}</td>
                                <td>{{ $item->discount_amount }}</td>
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
        </div>
    </main>
</body>

</html>
