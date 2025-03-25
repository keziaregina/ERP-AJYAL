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
        Registers Report
    </div>

    <p class="date">
        Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
    </p>

    <table>
        <thead>
            <tr>
                <th class="indexing" rowspan="2">#</th>
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
                    $total =
                        $item->total_card_payment +
                        $item->total_cheque_payment +
                        $item->total_cash_payment +
                        $item->total_bank_transfer_payment +
                        $item->total_other_payment +
                        $item->total_advance_payment +
                        $item->total_custom_pay_1 +
                        $item->total_custom_pay_2 +
                        $item->total_custom_pay_3 +
                        $item->total_custom_pay_4 +
                        $item->total_custom_pay_5 +
                        $item->total_custom_pay_6 +
                        $item->total_custom_pay_7;
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
        </tbody>
        <tfoot>
            
            <tr class="total">
                <td class="bold" colspan="10">Total</td>
            </tr>
            <tr class="total">
                <td class="bold" colspan="2">Total Card Slips</td>
                <td class="bold" colspan="2">Total Cheques</td>
                <td class="bold">Total Cash</td>
                <td class="bold" colspan="2">Total Bank Transfer</td>
                <td class="bold" colspan="2">Total Advance Payment</td>
                <td class="bold">Cust. Payment 1</td>
            </tr>
            <tr>
                <td colspan="2">{{ number_format(collect($report)->sum('total_card_payment') ?: '0', 3) }} {{ $currency }}
                </td>
                <td colspan="2">{{ number_format(collect($report)->sum('total_cheque_payment') ?: '0', 3) }} {{ $currency }}
                </td>
                <td>{{ number_format(collect($report)->sum('total_cash_payment') ?: '0', 3) }} {{ $currency }}</td>
                <td colspan="2">
                    {{ number_format(collect($report)->sum('total_bank_transfer_payment') ?: '0', 3) }} {{ $currency }}</td>
                <td colspan="2">{{ number_format(collect($report)->sum('total_advance_payment') ?: '0', 3) }}
                    {{ $currency }}</td>
                <td>{{ number_format(collect($report)->sum('total_custom_pay_1') ?: '0', 3) }} {{ $currency }}</td>
            </tr>
            <tr class="total">
                <td class="bold" colspan="2">Cust. Payment 2</td>
                <td class="bold" colspan="2">Cust. Payment 3</td>
                <td class="bold">Cust. Payment 4</td>
                <td class="bold" colspan="2">Cust. Payment 5</td>
                <td class="bold" colspan="2">Cust. Payment 6</td>
                <td class="bold">Cust. Payment 7</td>
            </tr>
            <tr>
                <td colspan="2">{{ number_format(collect($report)->sum('total_custom_pay_2') ?: '0', 3) }} {{ $currency }}
                </td>
                <td colspan="2">{{ number_format(collect($report)->sum('total_custom_pay_3') ?: '0', 3) }} {{ $currency }}
                </td>
                <td>{{ number_format(collect($report)->sum('total_custom_pay_4') ?: '0', 3) }} {{ $currency }}</td>
                <td colspan="2">{{ number_format(collect($report)->sum('total_custom_pay_5') ?: '0', 3) }} {{ $currency }}
                </td>
                <td colspan="2">{{ number_format(collect($report)->sum('total_custom_pay_6') ?: '0', 3) }} {{ $currency }}
                </td>
                <td>{{ number_format(collect($report)->sum('total_custom_pay_7') ?: '0', 3) }} {{ $currency }}</td>
            </tr>
            <tr class="total">
                <td class="bold" colspan="10">Subtotal</td>
            </tr>
            <tr>
                <td colspan="10">{{ number_format(collect($report)->sum('total') ?: '0', 3) }} {{ $currency }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
