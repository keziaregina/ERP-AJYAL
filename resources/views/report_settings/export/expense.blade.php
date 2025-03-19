<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Report</title>
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
            background-color: #f8fafc;
        }

        .box table {
            margin-bottom: 10px;
            margin-left: 10px;
            border: none;
            width: 100%;
            border-collapse: collapse;
        }

        .box th, .box td {
            border: none;
            text-align: left;
            padding: 10px;
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
    </style>
</head>
<body>

    <img class="logo" src="{{ $logo }}" alt="logo">
    <h1>Ajyal Al - Madina</h1>

    {{ Log::info("CUSTOMER & SUPPLIER -------------------------------------------------->") }}
    {{ Log::info(json_encode($report,JSON_PRETTY_PRINT)) }}

    <div class="report-title">
        Expense Report - AJYAL AL-MADINA AL ASRIA
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
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
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['category'] ?: '-' }}</td>
                        <td>{{ $item['total_expense'] ?: '-' }}</td>
                        @php
                            $total_expense += $expense['total_expense'];
                        @endphp
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No data available in table</td>
                    </tr>
                @endforelse
                <tr class="total-row">
                    <td colspan="2">Total:</td>
                    <td>{{ number_format($total_expense, 3) }} SAR</td>
                </tr>
        </tbody>
    </table>
</body>
</html>
