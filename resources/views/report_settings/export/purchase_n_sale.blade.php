<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Purchase & Sales Summary</title>

    <style>
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
            margin-bottom: 15px;
            color: #444;
            font-size: 18px;
        }

        .card table {
            width: 100%;
            border-collapse: collapse;
        }

        .card table td {
            padding: 6px 0;
            color: #333;
        }

        .label {
            font-weight: bold;
        }

        .info {
            color: #3498db;
            cursor: pointer;
            margin-left: 5px;
        }

        .overall {
            margin-top: 20px;
            background-color: #bebebe;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .overall-title{
            margin: 0 0 10px 0;
            color: #444;
            font-size: 16px;
        }

        .overall h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #444;
        }

        .negative {
            color: #e74c3c;
            /* font-weight: bold; */
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
        {{-- <span class="arabic">{{ env('APP_TITLE') }}</span> --}}
    </header>
    <main>
        <div class="report-title">
            Purchase and Sales Report - AJYAL AL-MADINA AL ASRIA
        </div>
        <div class="container">
            <p>
                Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
            </p>
            <div class="card">
                <h3>Purchases</h3>
                <table>
                    <tr>
                        <td class="label">Total Purchase:</td>

                        <td>{{ $report['purchase']['total_purchase_exc_tax'] ? $report['purchase']['total_purchase_exc_tax'] : '0' }} SAR</td>
                    </tr>
                    <tr>
                        <td class="label">Purchase Including tax:</td>
                        <td>{{ $report['purchase']['total_purchase_inc_tax'] ?: '0' }} SAR</td>
                    </tr>
                    <tr>
                        <td class="label">Total Purchase Return Including Tax:</td>
                        <td>{{ $report['total_purchase_return'] ?: '0' }} SAR</td>
                    </tr>
                    <tr>
                        <td class="label">Purchase Due: </td>
                        <td>{{ $report['purchase']['purchase_due'] ?: '0' }} SAR</td>
                    </tr>
                </table>
            </div>

            <div class="card">
                <h3>Sales</h3>
                <table>
                    <tr>
                        <td class="label">Total Sale:</td>
                        <td>{{ $report['sell']['total_sell_exc_tax'] ? $report['sell']['total_sell_exc_tax'] : '0' }} SAR</td>
                    </tr>
                    <tr>
                        <td class="label">Sale Including tax:</td>
                        <td>{{ $report['sell']['total_sell_inc_tax'] ? $report['sell']['total_sell_inc_tax'] : '0' }} SAR</td>
                    </tr>
                    <tr>
                        <td class="label">Total Sell Return Including Tax:</td>
                        <td>{{ $report['total_sell_return'] ? $report['total_sell_return'] : '0' }} SAR</td>
                    </tr>
                    <tr>
                        <td class="label">Sale Due:</td>
                        <td>{{ $report['sell']['invoice_due'] ? $report['sell']['invoice_due'] : '0' }} SAR</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="overall">
            <h4 class="overall-title">Overall</h4>
            <h4>(Sale - Sell Return) - (Purchase - Purchase Return)</h4>
            <table>
                <tr class="negative">
                    <td>Sale - Purchase</td>
                    <td>:</td>
                    <td>{{ $report['difference']['total'] ?: '0' }} SAR</td>
                </tr>
                <tr class="negative">
                    <td>Due amount</td>
                    <td>:</td>
                    <td>{{ $report['difference']['due'] ?: '0' }} SAR</td>
                </tr>
            </table>
        </div>
    </main>
</body>

</html>
