<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purchase Payment Summary</title>
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

        .total {
            font-weight: bold;
            background-color: #f4f7fa;
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
            Purchase Payments Report - AJYAL AL-MADINA AL ASRIA
        </div>
        <div class="container">
            <p>
                Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
            </p>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Ref. No</th>
                        <th>Paid on</th>
                        <th>Amount</th>
                        <th>Supplier</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->payment_ref_no }}</td>
                        <td>{{ $item->paid_no ?: '-' }}</td>
                        <td>{{ number_format($item->amount, 3) }} SAR</td>
                        <td>{!! $item->supplier !!}</td>
                        <td>{{ $item->method }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            No Data Available
                        </td>
                    </tr>
                    @endforelse
                    <tr class="total">
                        <td colspan="3">Total:</td>
                        <td>{{ number_format(collect($report)->sum('amount'), 3) }} SAR</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
