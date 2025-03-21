<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Suppliers Report</title>
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
        Customer and Supplier Report
    </div>

    <p class="date">
        Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
    </p>

    <table>
        <thead>
            <tr>
                <th class="indexing">#</th>
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
                    <td class="indexing">{{ $index + 1 }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ number_format($item['total_purchase'], 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ number_format($item['total_purchase_return'], 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ number_format($item['total_invoice'], 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ number_format($item['total_sell_return'], 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ number_format($item['opening_balance'], 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ $item['due'] }} {{ $currency }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No Data Available</td>
                </tr>
            @endforelse
            
        </tbody>
        <tfoot>
            <tr class="total">
                <td class="bold" colspan="2">Total:</td>
                <td>{{ number_format(collect($report)->sum('total_purchase'), 3) ?: '0' }} {{ $currency }}</td>
                <td>{{ number_format(collect($report)->sum('total_purchase_return'), 3) ?: '0' }} {{ $currency }}</td>
                <td>{{ number_format(collect($report)->sum('total_invoice'), 3) ?: '0' }} {{ $currency }}</td>
                <td>{{ number_format(collect($report)->sum('total_sell_return'), 3) ?: '0' }} {{ $currency }}</td>
                <td>{{ number_format(collect($report)->sum('opening_balance'), 3) ?: '0' }} {{ $currency }}</td>
                <td>{{ number_format(collect($report)->sum('due'), 3) ?: '0' }} {{ $currency }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
