<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stock Adjustments Report</title>
    {{-- <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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

        .card {
            background-color: #b6c9db;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            min-width: 300px;
            margin-bottom: 20px;
        }

        .card h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #444;
            font-size: 18px;
        }

        .label {
            font-size: 18px;
            color: rgb(82, 77, 77);
        }

        .card table,
        .card th,
        .card td {
            border: none;
            padding: 0px 8px;
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #2C3E50;
            color: white;
            font-weight: bold;
        }

        /* tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        } */
    </style> --}}

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

        .box {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8fafc;
        }

        .box table {
            margin: 10px;
            border: none;
            width: 100%;
            border-collapse: collapse;
        }

        .box th,
        .box td {
            border: none;
            text-align: left;
            padding: 10px;
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
        Stock Adjustments Report
    </div>

    <p class="date">
        Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
    </p>

    <div>
        <div class="box">
            <table>
                <tr>
                    <td class="label">
                        Total Normal
                    </td>
                    <td class="label">
                        Total Abnormal
                    </td>
                    <td class="label">
                        Total Stock Adjustment
                    </td>
                    <td class="label">
                        Total Amount Recovered
                    </td>
                </tr>
                <tr>
                    <td>{{ number_format($report['details']->total_normal ?: '0', 3) }} SAR</h3>
                    <td>{{ number_format($report['details']->total_abnormal ?: '0', 3) }} SAR</h3>
                    <td>{{ number_format($report['details']->total_amount ?: '0', 3) }} SAR</h3>
                    <td>{{ number_format($report['details']->total_recovered ?: '0', 3) }} SAR</h3>
                </tr>
                {{-- <tr>
                        <td>{{ number_format(($report['opening_stock'] - $report['total_purchase'] + $report['closing_stock']) ?: '0', 3) }} SAR</h3>
                        <td>{{ number_format($report['gross_profit'] ?: '0', 3) }} SAR</h3>
                        <td>{{ number_format($report['net_profit'] ?: '0', 3) }} SAR</h3>
                    </tr> --}}
            </table>
        </div>

        <div>
            <h3 class="label">Stock Adjustments</h3>
            <table>
                <thead>
                    <tr>
                        <th class="indexing">#</th>
                        <th>Date</th>
                        <th>Ref. No</th>
                        <th>Location</th>
                        <th>Adjustment Type</th>
                        <th>Total Amount</th>
                        <th>Total Amount Recovered</th>
                        <th>Reason</th>
                        <th>Added By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report['collection'] as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</th>
                            <td>{{ $item->transaction_date }}</th>
                            <td>{{ $item->ref_no }}</th>
                            <td>{{ $item->location_name }}</th>
                            <td>{{ $item->adjustment_type }}</th>
                            <td>{{ $item->final_total }}</th>
                            <td>{{ $item->total_amount_recovered }}</th>
                            <td>{{ $item->additional_notes }}</th>
                            <td>{{ $item->added_by }}</th>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center;">No data available in table</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
