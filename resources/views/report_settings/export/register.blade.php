<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Report</title>

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

        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
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

        .total {
            font-weight: bold;
            background-color: #f4f7fa;
        }
        .logo {
            width: 100px;
            height: 100px;
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
            Registers Report - AJYAL AL-MADINA AL ASRIA
        </div>
        <div class="container">
            <p>
                Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
            </p>
            <div style="margin-bottom: 20px;">
                <table border="0">
                    <thead>
                        <tr>
                            <th rowspan="2">#</th>
                            <th>Open At</th>
                            <th>Closed At</th>
                            <th>Location</th>
                            <th>User</th>
                            <th>Total Card Slips</th>
                            <th>Cheques</th>
                            <th>Cash</th>
                            <th>Bank Transfers</th>
                            <th>Advance Payments</th>
                        </tr>
                        <tr>
                            <th>Cust Payment 1</th>
                            <th>Cust Payment 2</th>
                            <th>Cust Payment 3</th>
                            <th>Cust Payment 4</th>
                            <th>Cust Payment 5</th>
                            <th>Cust Payment 6</th>
                            <th>Cust Payment 7</th>
                            <th>Other Payment</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($report as $index => $item)
                        @php
                            $total = $item->total_card_payment + $item->total_cheque_payment + $item->total_cash_payment + $item->total_bank_transfer_payment + $item->total_other_payment + $item->total_advance_payment + $item->total_custom_pay_1 + $item->total_custom_pay_2 + $item->total_custom_pay_3 + $item->total_custom_pay_4 + $item->total_custom_pay_5 + $item->total_custom_pay_6 + $item->total_custom_pay_7;;
                        @endphp
                            
                        <tr>
                            <td rowspan="2">{{ $index + 1 }}</td>
                            <td>-</td>
                            <td>{{ $item->status === 'close' ? $item->closed_at : '-' }}</td>
                            <td>{{ $item->location_name }}</td>
                            <td>{!! $item->user_name !!}</td>
                            <td>{{ number_format($item->total_card_payment ?: '0', 3) }}</td>
                            <td>{{ number_format($item->total_cheque_payment ?: '0', 3) }}</td>
                            <td>{{ number_format($item->total_cash_payment ?: '0', 3) }}</td>
                            <td>{{ number_format($item->total_bank_transfer_payment ?: '0', 3) }}</td>
                            <td>{{ number_format($item->total_advance_payment ?: '0', 3) }}</td>
                        </tr>
                        <tr>
                            <td>{{ number_format($item->total_custom_pay_1 ?: '0') }}</td>
                            <td>{{ number_format($item->total_custom_pay_2 ?: '0') }}</td>
                            <td>{{ number_format($item->total_custom_pay_3 ?: '0') }}</td>
                            <td>{{ number_format($item->total_custom_pay_4 ?: '0') }}</td>
                            <td>{{ number_format($item->total_custom_pay_5 ?: '0') }}</td>
                            <td>{{ number_format($item->total_custom_pay_6 ?: '0') }}</td>
                            <td>{{ number_format($item->total_custom_pay_7 ?: '0') }}</td>
                            <td>{{ number_format($item->total_other_payment ?: '0') }}</td>
                            <td>{{ number_format($total ?: '0') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">No Data Available</td>
                        </tr>
                        @endforelse
                        <tr class="total">
                            <td colspan="10">Total</td>
                        </tr>
                        <tr class="total">
                            <td colspan="2">Total Card Slips</td>
                            <td colspan="2">Total Cheques</td>
                            <td>Total Cash</td>
                            <td colspan="2">Total Bank Transfer</td>
                            <td colspan="2">Total Advance Payment</td>
                            <td>Cust. Payment 1</td>
                        </tr>
                        <tr>
                            <td colspan="2">{{ number_format(collect($report)->sum('total_card_payment') ?: '0', 3) }} SAR</td>
                            <td colspan="2">{{ number_format(collect($report)->sum('total_cheque_payment') ?: '0', 3) }} SAR</td>
                            <td>{{ number_format(collect($report)->sum('total_cash_payment') ?: '0', 3) }} SAR</td>
                            <td colspan="2">{{ number_format(collect($report)->sum('total_bank_transfer_payment') ?: '0', 3) }} SAR</td>
                            <td colspan="2">{{ number_format(collect($report)->sum('total_advance_payment') ?: '0', 3) }} SAR</td>
                            <td>{{ number_format(collect($report)->sum('total_custom_pay_1') ?: '0', 3) }} SAR</td>
                        </tr>
                        <tr class="total">
                            <td colspan="2">Cust. Payment 2</td>
                            <td colspan="2">Cust. Payment 3</td>
                            <td>Cust. Payment 4</td>
                            <td colspan="2">Cust. Payment 5</td>
                            <td colspan="2">Cust. Payment 6</td>
                            <td>Cust. Payment 7</td>
                        </tr>
                        <tr>
                            <td colspan="2">{{ number_format(collect($report)->sum('total_custom_pay_2') ?: '0', 3) }} SAR</td>
                            <td colspan="2">{{ number_format(collect($report)->sum('total_custom_pay_3') ?: '0', 3) }} SAR</td>
                            <td>{{ number_format(collect($report)->sum('total_custom_pay_4') ?: '0', 3) }} SAR</td>
                            <td colspan="2">{{ number_format(collect($report)->sum('total_custom_pay_5') ?: '0', 3) }} SAR</td>
                            <td colspan="2">{{ number_format(collect($report)->sum('total_custom_pay_6') ?: '0', 3) }} SAR</td>
                            <td>{{ number_format(collect($report)->sum('total_custom_pay_7') ?: '0', 3) }} SAR</td>
                        </tr>
                        <tr class="total">
                            <td colspan="10">Subtotal</td>
                        </tr>
                        <tr>
                            <td colspan="10">{{ number_format(collect($report)->sum('total') ?: '0', 3) }} SAR</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>
