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
                            <div class="label">Profit Margin %</div>
                            <div class="value">{{ $report['stock_value']['profit_margin'] }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            <div style="margin-top: 20px;">
                <table border="0">
                    <thead>
                        <tr>
                            <th rowspan="2">#</th>
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
                            <td>{{ $item->unit_price }}</td>
                            <td>{{ $item->stock ?: '-' }}</td>
                            <td>{{ $item->stock_price ?: '-' }}</td>
                            <td>{{ $stock_price_by_sp }}</td>
                        </tr>
                        <tr>
                            <td>{{ $potential_profit }}</td>
                            <td>{{ $item->total_sold ?: '-' }} Bags</td>
                            <td>{{ $item->total_transfered ?: '-' }} Bags</td>
                            <td>{{ $item->total_adjusted ?: '-' }} Bags</td>
                            <td>{{ $item->product_custom_field1 ?: '-' }}</td>
                            <td>{{ $item->product_custom_field2 ?: '-' }}</td>
                            <td>{{ $item->product_custom_field3 ?: '-' }}</td>
                            <td>{{ $item->product_custom_field4 ?: '-' }}</td>
                            <td>{{ $item->total_mfg_stock ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>
