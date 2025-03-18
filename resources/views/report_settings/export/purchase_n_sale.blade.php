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

        .card {
            background-color: #f4f7fa;
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
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
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
            src: url("{{ storage_path('fonts/Amiri-Regular.ttf') }}") format("truetype");
        }
        .arabic {
            direction: rtl;
            /* text-align: right; */
            font-family: 'Amiri', sans-serif;
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
            <h3 style="text-center">
                Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
            </h3>
            <div class="card">
                <h3>Purchases</h3>
                <table>
                    <tr>
                        <td class="label">Total Purchase:</td>

                        <td>{{ $report['purchase']['total_purchase_exc_tax'] ? $report['purchase']['total_purchase_exc_tax'] : '0' }} <span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Purchase Including tax:</td>
                        <td>{{ $report['purchase']['total_purchase_inc_tax'] ?: '0' }} <span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total Purchase Return Including Tax:</td>
                        <td>{{ $report['total_purchase_return'] ?: '0' }} <span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Purchase Due: </td>
                        <td>{{ $report['purchase']['purchase_due'] ?: '0' }} <span class="arabic">{{ $currency }}</span></td>
                    </tr>
                </table>
            </div>

            <div class="card">
                <h3>Sales</h3>
                <table>
                    <tr>
                        <td class="label">Total Sale:</td>
                        <td>{{ $report['sell']['total_sell_exc_tax'] ? $report['sell']['total_sell_exc_tax'] : '0' }} <span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Sale Including tax:</td>
                        <td>{{ $report['sell']['total_sell_inc_tax'] ? $report['sell']['total_sell_inc_tax'] : '0' }} <span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Total Sell Return Including Tax:</td>
                        <td>{{ $report['total_sell_return'] ? $report['total_sell_return'] : '0' }} <span class="arabic">{{ $currency }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Sale Due:</td>
                        <td>{{ $report['sell']['invoice_due'] ? $report['sell']['invoice_due'] : '0' }} <span class="arabic">{{ $currency }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="overall">
            <h3>Overall</h3>
            <h4>(Sale - Sell Return) - (Purchase - Purchase Return)</h4>
            <table>
                <tr class="negative">
                    <td>Sale - Purchase</td>
                    <td>:</td>
                    <td>{{ $report['difference']['total'] ?: '0' }} <span class="arabic">{{ $currency }}</span></td>
                </tr>
                <tr class="negative">
                    <td>Due amount</td>
                    <td>:</td>
                    <td>{{ $report['difference']['due'] ?: '0' }} <span class="arabic">{{ $currency }}</span></td>
                </tr>
            </table>
        </div>
    </main>
</body>

</html>
