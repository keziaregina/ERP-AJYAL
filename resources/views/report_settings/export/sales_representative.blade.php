<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Representative Report</title>
    
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

        .header-box{
            margin-left: 20px;
        }

        .box {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8fafc;
        }

        .box table {
            border: none;
            width: 100%;
            border-collapse: collapse;
        }

        .box th, .box td {
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

        .box {
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f8fafc;
            padding: 10px;
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
        Sales Representatives Report
    </div>

    <p class="date">
        Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
    </p>

    <div>
        <p class="label">Summary</p>
        <div class="box">
            <table>
                <tr>
                    <td>
                        <div class="label">Total Sale - Total Sales Return :</div>
                        <div class="value">
                            {{ number_format($report['overall']['sell']['total_sell_exc_tax'], 3) ?: '0' }} SAR - 
                            {{ number_format($report['overall']['sell']['total_sell_return_exc_tax'], 3) ?: '0' }} SAR = 
                            {{ number_format($report['overall']['sell']['total_sell'], 3) ?: '0' }} SAR</div>
                    </td>
                    <td>
                        <div class="label">Total Expense : 
                        </div>
                        <div class="value">
                            {{ number_format($report['overall']['expense']['total_expense'], 3) ?: '0' }} SAR
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <h3 class="label">Sales</h3>
    <table>
        <thead>
            <tr>
                <th class="indexing">#</th>
                <th>Date</th>
                <th>Invoice No.</th>
                <th>Customer Name</th>
                <th>Location</th>
                <th>Payment Status</th>
                <th>Total Amount</th>
                <th>Total Paid</th>
                <th>Total Remaining</th>
            </tr>
        </thead>
        <tbody>
                @forelse ($report['collection']['sales'] as $index => $item)
                <tr>
                    <td class="indexing">{{ $index + 1 }}</td>
                    <td>{{ $item->transaction_date ?: '-' }}</td>
                    <td>{{ $item->invoice_no ?: '-' }}</td>
                    <td>{{ $item->conatct_name ?: '-' }}</td>
                    <td>{{ $item->business_location ?: '-' }}</td>
                    <td>{{ $item->payment_status ?: '-' }}</td>
                    <td>{{ number_format($item->final_total, 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ number_format($item->total_paid, 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ number_format($item->total_remaining, 3) ?: '0' }} {{ $currency }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">No data available in table</td>
                </tr>
                @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td class="bold" colspan="5">Total:</td>
                <td>{{ number_format(collect($report['collection']['sales'])->sum('final_total'), 3) }} {{ $currency }}</td>
                <td>{{ number_format(collect($report['collection']['sales'])->sum('total_paid'), 3) }} {{ $currency }}</td>
                <td>
                    <div><span class="bold">Sell Due </span>~ {{ number_format(collect($report['collection']['sales'])->sum('payment_due'), 3) }} {{ $currency }}</div>
                    <div><span class="bold">Sell Return Due</span> ~ {{ number_format(collect($report['collection']['sales'])->sum('sell_return_due'), 3) }} {{ $currency }}</div>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <h3 class="label">Expense</h3>
    <table>
        <thead>
            <tr>
                <th class="indexing">#</th>
                <th>Date</th>
                <th>Reference No.</th>
                <th>Expense Category</th>
                <th>Location</th>
                <th>Payment Status</th>
                <th>Total Amount</th>
                <th>Expense For</th>
                <th>Expense Note</th>
            </tr>
        </thead>
        <tbody>
                @forelse ($report['collection']['expense'] as $index => $item)
                <tr>
                    <td class="indexing">{{ $index + 1 }}</td>
                    <td>{{ $item->transaction_date ?: '-' }}</td>
                    <td>{{ $item->ref_no ?: '-' }}</td>
                    <td>{{ $item->category ?: '-' }}</td>
                    <td>{{ $item->location_name ?: '-' }}</td>
                    <td>{{ $item->payment_status ?: '-' }}</td>
                    <td>{{ number_format($item->final_total, 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ $item->expense_for ?: '-' }}</td>
                    <td>{{ $item->additional_notes ?: '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">No data available in table</td>
                </tr>
                @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td class="bold" colspan="6">Total:</td>
                <td>{{ number_format(collect($report['collection']['expense'])->sum('final_total'), 3) }} {{ $currency }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    </main>
</body>
</html>
