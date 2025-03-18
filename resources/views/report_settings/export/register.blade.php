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
        }

        th, td {
            text-align: left;
            padding: 10px;
        }

        th {
            background-color: #6b7280
        }

        td{
            background-color: #c2c2c2
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
    </style>
</head>

<body>
    <header>
        <img src="{{$logo }}" alt="logo">
        <h1 class="header">Ajyal Al - Madina</h1>
    </header>
    <main>
        <div class="container">
            <h3 style="text-center">
                Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
            </h3>

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
                        @foreach ($report as $index => $item)
                        @php
                            $total = $item->total_card_payment + $item->total_cheque_payment + $item->total_cash_payment + $item->total_bank_transfer_payment + $item->total_other_payment + $item->total_advance_payment + $item->total_custom_pay_1 + $item->total_custom_pay_2 + $item->total_custom_pay_3 + $item->total_custom_pay_4 + $item->total_custom_pay_5 + $item->total_custom_pay_6 + $item->total_custom_pay_7;;
                        @endphp
                            
                        <tr>
                            <td rowspan="2">{{ $index + 1 }}</td>
                            <td>-</td>
                            <td>{{ $item->status === 'close' ? $item->closed_at : '-' }}</td>
                            <td>{{ $item->location_name }}</td>
                            <td>{!! $item->user_name !!}</td>
                            <td>{{ $item->total_card_payment }}</td>
                            <td>{{ $item->total_cheque_payment }}</td>
                            <td>{{ $item->total_cash_payment }}</td>
                            <td>{{ $item->total_bank_transfer_payment }}</td>
                            <td>{{ $item->total_advance_payment }}</td>
                        </tr>
                        <tr>
                            <td>{{ $item->total_custom_pay_1 }}</td>
                            <td>{{ $item->total_custom_pay_2 }}</td>
                            <td>{{ $item->total_custom_pay_3 }}</td>
                            <td>{{ $item->total_custom_pay_4 }}</td>
                            <td>{{ $item->total_custom_pay_5 }}</td>
                            <td>{{ $item->total_custom_pay_6 }}</td>
                            <td>{{ $item->total_custom_pay_7 }}</td>
                            <td>{{ $item->total_other_payment }}</td>
                            <td>{{ $total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>
