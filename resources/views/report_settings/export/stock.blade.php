<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stock Report</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        header img {
            width: 100px;
        }

        header h1 {
            font-size: 12px;
        }

        .box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #f8fafc;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
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

        .center {
            text-align: center;
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
            <h3 style="text-center">
                Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
            </h3>
            
            <div class="box">
                <table border="0">
                    <tr>
                        <td>
                            <div class="label">Closing stock (By purchase price)</div>
                            <div class="value">{{ number_format($report['closing_stock_by_pp'], 3) }} <span class="arabic">{{ $currency }}</span></div>
                        </td>
                        <td>
                            <div class="label">Closing stock (By sale price)</div>
                            <div class="value">{{ number_format($report['closing_stock_by_sp'], 3) }} <span class="arabic">{{ $currency }}</span></div>
                        </td>
                        <td>
                            <div class="label">Potential profit</div>
                            <div class="value">{{ number_format($report['potential_profit'], 3) }} <span class="arabic">{{ $currency }}</span></div>
                        </td>
                        <td>
                            <div class="label">Profit Margin %</div>
                            <div class="value">{{ $report['profit_margin'] }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div>
                <table border="0">
                    <thead>
                        <th>SKU</th>
                        <th>Product</th>
                        <th>Variation</th>
                        <th>Category</th>
                        <th>Location</th>
                        <th>Unit Selling Price</th>
                        <th>Current Stock</th>
                        <th>Total Stock Price (By Purchase Price)</th>
                        <th>Total Stock Price (By Sale Price)</th>
                        <th>Potential Profit</th>
                        
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>
