<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Items Summary</title>
    <style>
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

        .header-box{
            margin-left: 20px;
        }

        .box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: #f8fafc;
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
        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .logo {
            width: 100px;
            height: 100px;
        }

        .total {
            font-weight: bold;
            background-color: #f4f7fa;
        }
        .center {
            text-align: center;
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
            Items Report - AJYAL AL-MADINA AL ASRIA
        </div>
        <div class="container">
            <p>
                Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
            </p>
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">#</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Purchase Date</th>
                        <th>Purchase</th>
                        <th>Supplier</th>
                        <th colspan="2">Purchase Price</th>
                    </tr>
                    <tr>
                        <th>Sell Date</th>
                        <th>Sale</th>
                        <th>Customer</th>
                        <th>Location</th>
                        <th>Sell Quantity</th>
                        <th>Selling Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report as $index => $item)
                    @php
                        $sell_date = \Carbon\Carbon::parse($item->sell_date);
                    @endphp
                    <tr>
                        <td rowspan="2">{{ $index + 1 }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->sku }}</td>
                        <td>{{ $item->purchase_date }}</td>
                        <td>{{ $item->purchase_ref_no }}</td>
                        <td>{{ $item->supplier }}</td>
                        <td colspan="2">{{ number_format($item->purchase_price, 3) }} SAR</td>
                    </tr>
                    <tr>
                        <td>
                            {{ $sell_date->format('Y-m-d') }}
                            <br>
                            {{ $sell_date->format('H:i:s') }}
                        </td>
                        <td>{{ $item->sale_invoice_no }}</td>
                        <td>{{ $item->customer }}</td>
                        <td>{{ $item->location }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->selling_price, 3) }} SAR</td>
                        <td>{{ number_format($item->subtotal, 3) }} SAR</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14">
                            No Data Available
                        </td>
                    </tr>
                    @endforelse
                    <tr class="total">
                        <td colspan="8">Total:</td>
                    </tr>
                    <tr class="total">
                        <td colspan="2">Purchase Price</td>
                        <td colspan="2">Quantity</td>
                        <td colspan="2">Selling Price</td>
                        <td colspan="2">Subtotal</td>
                    </tr>
                    <tr>
                        <td colspan="2">{{ number_format(collect($report)->sum('purchase_price'), 3) }} SAR</td>
                        <td colspan="2">{{ collect($report)->sum('quantity') }}</td>
                        <td colspan="2">{{ number_format(collect($report)->sum('row_selling_price'), 3) }} SAR</td>
                        <td colspan="2">{{ number_format(collect($report)->sum('subtotal'), 3) }} SAR</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
