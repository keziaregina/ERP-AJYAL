<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profit / Loss Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        header img {
            width: 100px;
        }
        .card {
            background-color: #f4f7fa;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .overall {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f7fa;
        }
        .datatable-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ $logo }}" alt="logo">
        <h1>Ajyal Al - Madina</h1>
        <span class="arabic">{{ env('APP_TITLE') }}</span>
    </header>
    <main>
        <div class="container">
            <h3>Report: {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}</h3>
            <div class="card">
                <h3>Purchases</h3>
                <table>
                    <tr>
                        <td class="label">Opening Stock (By purchase price):</td>
                        <td>{{ $report['opening_stock'] }}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total Purchase (Exc. tax, Discount):</td>
                        <td>{{ $report['total_purchase'] }}<span class="arabic">{{ $currency }}</span></td>
                    </tr>
                </table>
            </div>
            <div class="overall">
                <h3>COGS: {{ ($report['opening_stock'] - $report['total_purchase'] + $report['closing_stock']) }} <span class="arabic">{{ $currency }}</span></h3>
                <h3>Gross Profit: {{$report['gross_profit']}} <span class="arabic">{{ $currency }}</span></h3>
                <h3>Net Profit: {{$report['net_profit']}} <span class="arabic">{{ $currency }}</span></h3>
            </div>
            <div class="datatable-container">
                <h3>Stock Adjustments</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Date</th>
                            <th>Reference No</th>
                            <th>Location</th>
                            <th>Adjustment Type</th>
                            <th>Total Amount</th>
                            <th>Total Amount Recovered</th>
                            <th>Reason</th>
                            <th>Added By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="9" style="text-align: center;">No data available in table</td>
                        </tr>
                    </tbody>
                </table>
                <p>Showing 0 to 0 of 0 entries</p>
            </div>
        </div>
    </main>
</body>
</html>
