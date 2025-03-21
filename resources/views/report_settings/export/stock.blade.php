<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stock Report</title>

    {{-- <style>
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

        .header-box {
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

        .box th,
        .box td {
            border: none;
            text-align: left;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
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
    </style> --}}
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

        .header-box {
            margin-left: 20px;
        }

        .box {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8fafc;
        }

        .box table {
            margin: 10px;
            border: none;
            width: 100%;
            border-collapse: collapse;
        }

        .box th,
        .box td {
            border: none;
            text-align: left;
            padding: 10px;
        }

        .label {
            font-weight: bold;
            font-size: 12px
        }

        .value{
            font-size: 11px;
        }
        .data table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .data th,
        .data td {
            border: 0.5px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .data th {
            background-color: #2C3E50;
            color: white;
            font-weight: bold;
        }

        .data tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data tbody tr:nth-child(odd) {
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
        Stock Report
    </div>

    <p class="date">
        Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
    </p>

    <div class="box">
        <table>
            <tr>
                <td>
                    <div class="label">Closing stock (By purchase price)</div>
                    <div class="value">{{ number_format($report['stock_value']['closing_stock_by_pp'], 3) }} {{ $currency }}</div>
                </td>
                <td>
                    <div class="label">Closing stock (By sale price)</div>
                    <div class="value">{{ number_format($report['stock_value']['closing_stock_by_sp'], 3) }} {{ $currency }}</div>
                </td>
                <td>
                    <div class="label">Potential profit</div>
                    <div class="value">{{ number_format($report['stock_value']['potential_profit'], 3) }} {{ $currency }}</div>
                </td>
                <td>
                    <div class="label">Profit Margin</div>
                    <div class="value">{{ $report['stock_value']['profit_margin'] }} %</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="data">
        <table>
            <thead>
                <tr>
                    <th rowspan="2" class="indexing">#</th>
                    <th>SKU</th>
                    <th>Product</th>
                    <th>Variations</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Unit Selling Price</th>
                    <th>Current Stock</th>
                    <th>Current Stock (By Purchase Price)</th>
                    <th>Current Stock (By Sale Price)</th>
                </tr>
                <tr>
                    <th>Potential Profit</th>
                    <th>Total Unit Sold</th>
                    <th>Total Unit Transfered</th>
                    <th>Total Unit Adjusted</th>
                    <th>Cust Field1</th>
                    <th>Cust Field2</th>
                    <th>Cust Field3</th>
                    <th>Cust Field4</th>
                    <th>Current Stock (Manufacturing)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($report['stock_report'] as $index => $item)
                    @php
                        $stock = $item->stock ? $item->stock : 0;
                        $unit_selling_price = (float) $item->group_price > 0 ? $item->group_price : $item->unit_price;
    
                        $stock_price_by_sp = $stock * $unit_selling_price;
                        $potential_profit = (float) $stock_price_by_sp - (float) $item->stock_price;
                    @endphp
                    <tr>
                        <td rowspan="2">{{ $index + 1 }}</td>
                        <td>{{ $item->sku }}</td>
                        <td>{{ $item->product }}</td>
                        <td>{{ $item->variation_name ?: '-' }}</td>
                        <td>{{ $item->category_name ?: '-' }}</td>
                        <td>{{ $item->location_name ?: '-' }}</td>
                        <td>{{ number_format($item->unit_price, 3)  ?: '0' }} {{ $currency }}</td>
                        <td>{{ number_format($item->stock, 3) ?: '0' }} Bags</td>
                        <td>{{ number_format($item->stock_price,3)  ?: '0' }} {{ $currency }}</td>
                        <td>{{ number_format($stock_price_by_sp, 3)  ?: '0' }} {{ $currency }}</td>
                    </tr>
                    <tr>
                        <td>{{ number_format($potential_profit, 3) ?: '0' }} {{ $currency }}</td>
                        <td>{{ $item->total_sold ?: '-' }} Bags</td>
                        <td>{{ $item->total_transfered ?: '-' }} Bags</td>
                        <td>{{ $item->total_adjusted ?: '-' }} Bags</td>
                        <td>{{ $item->product_custom_field1 ?: '-' }}</td>
                        <td>{{ $item->product_custom_field2 ?: '-' }}</td>
                        <td>{{ $item->product_custom_field3 ?: '-' }}</td>
                        <td>{{ $item->product_custom_field4 ?: '-' }}</td>
                        <td>{{ number_format($item->total_mfg_stock, 3) ?: '0' }} Bags</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
