<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Report</title>
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
        Expenses Report
    </div>

    <p class="date">
        Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
    </p>

    <table>
        <thead>
            <tr>
                <th class="indexing">#</th>
                <th>Expense Categories</th>
                <th>Total Expense</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_expense = 0;
            @endphp
            @forelse ($report as $index => $item)
                <tr>
                    <td class="indexing">{{ $index + 1 }}</td>
                    <td>{{ $item['category'] ?: '-' }}</td>
                    <td>{{ number_format($item['total_expense'], 3) ?: '0' }} {{ $currency }}</td>
                    @php
                        $total_expense += $expense['total_expense'];
                    @endphp
                </tr>
            @empty
                <tr>
                    <td colspan="3">No data available in table</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td class="bold" colspan="2">Total:</td>
                <td>{{ number_format($total_expense, 3) ?: '0' }} {{ $currency }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
