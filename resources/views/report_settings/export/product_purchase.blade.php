<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Purchase Summary</title>
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
        Product Purchases Report
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
                <th>Supplier</th>
                <th>Ref. No</th>
                <th>Date</th>
            </tr>
            <tr>
                <th>Quantity</th>
                <th>Total Unit Adjusted</th>
                <th>Unit Purchase Price</th>
                <th colspan="2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $index => $item)
            <tr>
                <td class="indexing" rowspan="2">{{ $index + 1 }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->sub_sku }}</td>
                <td>{{ $item->supplier }}</td>
                <td>{{ $item->ref_no }}</td>
                <td>{{ $item->transaction_date }}</td>
            </tr>
            <tr>
                <td>{{ number_format($item->purchase_qty, 3) }}</td>
                <td>{{ number_format($item->quantity_adjusted, 3) }}</td>
                <td>{{ number_format($item->unit_purchase_price, 3) ?: '0' }} {{ $currency }}</td>
                <td colspan="2">{{ number_format($item->subtotal, 3) ?: '0' }} {{ $currency }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    No Data Available
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td class="bold" colspan="6">Total:</td>
            </tr>
            <tr class="total">
                <td class="bold" colspan="3">Unit Purchase Price</td>
                <td class="bold" colspan="3">Subtotal</td>
            </tr>
            <tr>
                <td colspan="3">{{ number_format(collect($report)->sum('quantity_adjusted'), 3) ?: '0' }} {{ $currency }}</td>
                <td colspan="3">{{ number_format(collect($report)->sum('subtotal'), 3) ?: '0' }} {{ $currency }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
