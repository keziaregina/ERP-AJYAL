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

        header img {
            width: 100px;
        }

        .header {
            font-size: 12px;
        }

        .box {
            border: 1px solid #dcdcdc;
            background-color: #ffffff;
            border-radius: 6px;
            padding: 15px;
            margin: 50px auto;
            width: 90%;
            max-width: 600px;
        }

        .title {
            font-size: 12pt;
            color: #333333;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-text {
            font-size: 14pt;
            color: #555555;
            font-weight: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 10px;
        }

        th {
            background-color: #6b7280
        }

        td{
            background-color: #c2c2c2
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

        .center {
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(@$logo)) }}" alt="logo">
        <h1 class="header">Ajyal Al - Madina</h1>
    </header>
    <main>
        <div class="container">
            <h3 style="text-center">
                Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
            </h3>
            
            <div class="box">
                <div class="title">
                    Overall (Input - Output - Expense)
                </div>
                <div class="info-text">
                    Output Tax - Input Tax - Expense Tax : {{ number_format($report['tax_diff']) }}
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <h1 style="margin-bottom: 15px">Input Tax (Purchase)</h1>

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
                                <td>{{ $item->c.name }}</td>
                                <td>{{ $item->c.tax_number }}</td>
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
                <h1 style="margin-bottom: 15px">Output Tax (Sales)</h1>

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
                                <td>{{ $item->c.name }}</td>
                                <td>{{ $item->c.tax_number }}</td>
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
                <h1 style="margin-bottom: 15px">Expense Tax</h1>

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
                                <td>{{ $item->c.tax_number }}</td>
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
