<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Sell Summary</title>
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
        Product Sells Report
    </div>

    <p class="date">
        Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
    </p>
    <table>
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th>Product</th>
                <th>SKU</th>
                <th>Customer Name</th>
                <th>Contact ID</th>
                <th>Invoice No.</th>
                <th>Date</th>
            </tr>
            <tr>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Tax</th>
                <th>Price (Inc. Tax)</th>
                <th>Total</th>
                <th>Payment Method</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $index => $item)
            <tr>
                <td rowspan="2">{{ $index + 1 }}</td>
                <td>{{ $item->product_name ?: '-' }}</td>
                <td>{{ $item->sub_sku ?: '-' }}</td>
                <td>{{ $item->customer ?: '-' }}</td>
                <td>{{ $item->contact_id ?: '-' }}</td>
                <td>{{ $item->invoice_no ?: '-' }}</td>
                <td>{{ $item->transaction_date ?: '-' }}</td>
            </tr>
            <tr>
                <td>{{ number_format($item->sell_qty, 3) ?: '0' }}</td>
                <td>{{ number_format($item->unit_price, 3) ?: '0' }}</td>
                <td>{{ number_format($item->discount_amount, 3) ?: '0' }}</td>
                <td>{{ number_format($item->tax, 3) ?: '0' }}</td>
                <td>{{ number_format($item->unit_sale_price, 3) ?: '0' }} {{ $currency }}</td>
                <td>{{ number_format($item->subtotal, 3) ?: '0' }} {{ $currency }}</td>
                <td>{{ $item->payment_methods ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    No Data Available
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td class="bold" colspan="4">Total:</td>
                <td class="bold" colspan="2">Subtotal</td>
                <td>{{ number_format(collect($report)->sum('subtotal'), 3) ?: '0' }} {{ $currency }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
