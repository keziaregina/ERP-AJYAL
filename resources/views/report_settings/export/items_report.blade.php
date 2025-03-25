<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Items Summary</title>
    
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
        Items Report
    </div>

    <p class="date">
        Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
    </p>

    <table>
        <thead>
            <tr>
                <th class="indexing" rowspan="2">#</th>
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
                    <td class="indexing" rowspan="2">{{ $index + 1 }}</td>
                    <td>{{ $item->product_name ?: '-' }}</td>
                    <td>{{ $item->sku ?: '-' }}</td>
                    <td>{{ $item->purchase_date ?: '-' }}</td>
                    <td>{{ $item->purchase_ref_no ?: '-' }}</td>
                    <td>{{ $item->supplier ?: '-' }}</td>
                    <td colspan="2">{{ number_format($item->purchase_price, 3) ?: '0' }} {{ $currency }}</td>
                </tr>
                <tr>
                    <td>
                        {{ $sell_date->format('Y-m-d') }}
                        <br>
                        {{ $sell_date->format('H:i:s') }}
                    </td>
                    <td>{{ $item->sale_invoice_no ?: '-' }}</td>
                    <td>{{ $item->customer ?: '-' }}</td>
                    <td>{{ $item->location ?: '-' }}</td>
                    <td>{{ $item->quantity ?: '-' }}</td>
                    <td>{{ number_format($item->selling_price, 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ number_format($item->subtotal, 3) ?: '0' }} {{ $currency }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14">
                        No Data Available
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td class="bold" colspan="8">Total:</td>
            </tr>
            <tr class="total">
                <td class="bold" colspan="2">Purchase Price</td>
                <td class="bold" colspan="2">Quantity</td>
                <td class="bold" colspan="2">Selling Price</td>
                <td class="bold" colspan="2">Subtotal</td>
            </tr>
            <tr>
                <td colspan="2">{{ number_format(collect($report)->sum('purchase_price'), 3) ?: '0' }} {{ $currency }}</td>
                <td colspan="2">{{ number_format(collect($report)->sum('quantity'), 3) ?: '0' }}</td>
                <td colspan="2">{{ number_format(collect($report)->sum('row_selling_price'), 3) ?: '0' }} {{ $currency }}</td>
                <td colspan="2">{{ number_format(collect($report)->sum('subtotal'), 3) ?: '0' }} {{ $currency }}</td>
            </tr>

        </tfoot>
    </table>
</body>

</html>
