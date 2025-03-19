<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Representative Report</title>
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
        Sales Representative Report - AJYAL AL-MADINA AL ASRIA
    </div>

    <div>
        <div class="box">
            <h3 class="header-box">Summary</h3>
            <table border="0">
                <tr>
                    <td>
                        <div class="label">Total Sale - Total Sales Return :</div>
                        <div class="value">
                            {{ number_format($report['overall']['sell']['total_sell_exc_tax'], 3) ?: '0' }} SAR - 
                            {{ number_format($report['overall']['sell']['total_sell_return_exc_tax'], 3) ?: '0' }} SAR = 
                            {{ number_format($report['overall']['sell']['total_sell'], 3) ?: '0' }} SAR</div>
                    </td>
                </tr>
                <tr>
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
                <th>#</th>
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
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->transaction_date ?: '-' }}</td>
                    <td>{{ $item->invoice_no ?: '-' }}</td>
                    <td>{{ $item->conatct_name ?: '-' }}</td>
                    <td>{{ $item->business_location ?: '-' }}</td>
                    <td>{{ $item->payment_status ?: '-' }}</td>
                    <td>{{ number_format($item->final_total, 3) ?: '0' }} SAR</td>
                    <td>{{ number_format($item->total_paid, 3) ?: '0' }} SAR</td>
                    <td>{{ number_format($item->total_remaining, 3) ?: '0' }} SAR</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">No data available in table</td>
                </tr>
                @endforelse
                <tr class="total-row">
                    <td colspan="5">Total:</td>
                    <td>{{ number_format(collect($report['collection']['sales'])->sum('final_total'), 3) }} SAR</td>
                    <td>{{ number_format(collect($report['collection']['sales'])->sum('total_paid'), 3) }} SAR</td>
                    <td>
                        <div>Sell Due ~ {{ number_format(collect($report['collection']['sales'])->sum('payment_due'), 3) }} SAR</div>
                        <div>Sell Return Due ~ {{ number_format(collect($report['collection']['sales'])->sum('sell_return_due'), 3) }} SAR</div>
                    </td>
                </tr>
        </tbody>
    </table>

    <h3 class="label">Expense</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
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
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->transaction_date ?: '-' }}</td>
                    <td>{{ $item->ref_no ?: '-' }}</td>
                    <td>{{ $item->category ?: '-' }}</td>
                    <td>{{ $item->location_name ?: '-' }}</td>
                    <td>{{ $item->payment_status ?: '-' }}</td>
                    <td>{{ number_format($item->final_total, 3) ?: '0' }}</td>
                    <td>{{ $item->expense_for ?: '-' }}</td>
                    <td>{{ $item->additional_notes ?: '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">No data available in table</td>
                </tr>
                @endforelse
                <tr class="total-row">
                    <td colspan="6">Total:</td>
                    <td>{{ number_format(collect($report['collection']['expense'])->sum('final_total'), 3) }} SAR</td>
                    <td colspan="2"></td>
                </tr>
        </tbody>
    </table>

</body>
</html>
