<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purchase Payment Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            /* background-color: #f9f9f9; */
        }

        header img {
            width: 100px;
        }

        header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }

        .total {
            font-weight: bold;
            background-color: #f4f7fa;
        }

        @font-face {
            font-family: 'Amiri';
            src: url("{{ asset('fonts/Amiri-Regular.ttf') }}") format("truetype");
        }

        .arabic {
            direction: rtl;
            text-align: right;
            font-family: 'Amiri', sans-serif;
            font-weight: normal;
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
            <h3>Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>Ref. No</th>
                        <th>Paid on</th>
                        <th>Amount</th>
                        <th>Supplier</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report as $item)
                    <tr>
                        <td>{{ $item->payment_ref_no }}</td>
                        <td>{{ $item->paid_no }}</td>
                        <td>{{ $item->amount }}</td>
                        <td>{{ $item->supplier }}</td>
                        <td>{{ $item->method }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            No Data Available
                        </td>
                    </tr>
                    @endforelse
                    <tr class="total">
                        <td colspan="2">Total:</td>
                        <td>{{ number_format(collect($report)->sum('amount'), 3) }} SAR</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
