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

        .date {
            font-size: 11px;
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

        .separator {
            border: none !important;
            background-color: #ffffff !important;
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
        }

        .overall-title {
            color: #2C3E50;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        .overall {
            font-size: 12px;
            color: black;
        }

        .negative {
            color: #e74c3c;
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
        Purchases and Sales Report
    </div>

    <p class="date">
        Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
    </p>

    <table>
        <thead>
            <tr>
                <th colspan="2">Purchases</th>
                <th class="separator"></th>
                <th colspan="2">Sales</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="label">Total Purchase:</td>
                <td>{{ number_format($report['purchase']['total_purchase_exc_tax'], 3) ?: '0' }} {{ $currency }}
                </td>
                <td class="separator"></td>
                <td class="label">Total Sale:</td>
                <td>{{ number_format($report['sell']['total_sell_exc_tax'], 3) ?: '0' }} {{ $currency }}</td>
            </tr>
            <tr>
                <td class="label">Purchase Including tax:</td>
                <td>{{ number_format($report['purchase']['total_purchase_inc_tax'], 3) ?: '0' }}
                    {{ $currency }}</td>
                <td class="separator"></td>
                <td class="label">Sale Including tax:</td>
                <td>{{ number_format($report['sell']['total_sell_inc_tax'], 3) ?: '0' }} {{ $currency }}</td>
            </tr>
            <tr>
                <td class="label">Total Purchase Return Including Tax:</td>
                <td>{{ number_format($report['total_purchase_return'], 3) ?: '0' }} {{ $currency }}</td>
                <td class="separator"></td>
                <td class="label">Total Sell Return Including Tax:</td>
                <td>{{ number_format($report['total_sell_return'], 3) ?: '0' }} {{ $currency }}</td>
            </tr>
            <tr>
                <td class="label">Purchase Due: </td>
                <td>{{ number_format($report['purchase']['purchase_due'], 3) ?: '0' }} {{ $currency }}</td>
                <td class="separator"></td>
                <td class="label">Sale Due:</td>
                <td>{{ number_format($report['sell']['invoice_due'], 3) ?: '0' }} {{ $currency }}</td>
            </tr>
        </tbody>
    </table>

    <br>
    <br>
    <div class="overall">
        <span class="overall-title">Overall</span>
        <h4>(Sale - Sell Return) - (Purchase - Purchase Return)</h4>
        <div>
            Sale - Purchase : <span class="negative">{{ number_format($report['difference']['total'], 3) ?: '0' }} {{ $currency }}</span>
        </div>
        <div>
            Due Amount : <span class="negative">{{ number_format($report['difference']['due'], 3) ?: '0' }} {{ $currency }}</span>
        </div>
    </div>
</body>

</html>
