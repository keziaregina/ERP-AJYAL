<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Suppliers Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: left;
        }

        header img {
            width: 100px;
        }

        header h1 {
            font-size: 15px;
        }
        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
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

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
    </style>
</head>
<body>

    <header>
        <img class="logo" src="{{ $logo }}" alt="logo">
        <h1>Ajyal Al - Madina</h1>
    </header>

    {{ Log::info("CUSTOMER & SUPPLIER -------------------------------------------------->") }}
    {{ Log::info(json_encode($report,JSON_PRETTY_PRINT)) }}

    <div class="report-title">
        Customers - Suppliers Report - AJYAL AL-MADINA AL ASRIA
    </div>

    <p>
        Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Contact</th>
                <th>Total Purchase</th>
                <th>Total Purchase Return</th>
                <th>Total Sale</th>
                <th>Total Sell Return</th>
                <th>Opening Balance Due</th>
                <th>Due Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ number_format($item['total_purchase'], 3) }} SAR</td>
                    <td>{{ number_format($item['total_purchase_return'], 3) }} SAR</td>
                    <td>{{ number_format($item['total_invoice'], 3) }} SAR</td>
                    <td>{{ number_format($item['total_sell_return'], 3) }} SAR</td>
                    <td>{{ number_format($item['opening_balance'], 3) }} SAR</td>
                    <td>{{ $item['due'] }} SAR</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No Data Available</td>
                </tr>
            @endforelse
            
            {{-- Total Row --}}
            <tr class="total-row">
                <td colspan="2">Total:</td>
                <td>{{ number_format(collect($report)->sum('total_purchase'), 3) }} SAR</td>
                <td>{{ number_format(collect($report)->sum('total_purchase_return'), 3) }} SAR</td>
                <td>{{ number_format(collect($report)->sum('total_invoice'), 3) }} SAR</td>
                <td>{{ number_format(collect($report)->sum('total_sell_return'), 3) }} SAR</td>
                <td>{{ number_format(collect($report)->sum('opening_balance'), 3) }} SAR</td>
                <td>{{ number_format(collect($report)->sum('due'), 3) }} SAR</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
