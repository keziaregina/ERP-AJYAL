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
            /* background-color: #f9f9f9; */
        }

        header img {
            width: 100px;
        }

        header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }

        .total {
            font-weight: bold;
            background-color: #f4f7fa;
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
            <h3>Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Customer Name</th>
                        <th>Contact ID</th>
                        <th>Invoice No.</th>
                        <th>Date</th>
                        <th>Unit Price</th>
                        <th>Discount</th>
                        <th>Tax</th>
                        <th>Price (Inc. Tax)</th>
                        <th>Total</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->sub_sku }}</td>
                        <td>{{ $item->customer }}</td>
                        <td>{{ $item->contact_id }}</td>
                        <td>{{ $item->invoice_no }}</td>
                        <td>{{ $item->transaction_date }}</td>
                        <td>{{ $item->sell_qty }}</td>
                        <td>{{ $item->unit_price }}</td>
                        <td>{{ $item->discount_amount }}</td>
                        <td>{{ $item->tax }}</td>
                        <td>{{ number_format($item->unit_sale_price, 3) }} SAR</td>
                        <td>{{ number_format($item->subtotal, 3) }} SAR</td>
                        <td>{{ $item->payment_methods }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13">
                            No Data Available
                        </td>
                    </tr>
                    @endforelse
                    <tr class="total">
                        <td colspan="11">Total:</td>
                        <td>{{ number_format(collect($report)->sum('subtotal'), 3) }} SAR</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
