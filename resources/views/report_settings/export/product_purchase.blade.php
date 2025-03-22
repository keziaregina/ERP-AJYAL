<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Purchase Summary</title>
    {{-- <style>
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
    </style> --}}
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
            Product Purchase Report - AJYAL AL-MADINA AL ASRIA
        </div>
        <div class="container">
            <p>
                Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
            </p>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Supplier</th>
                        <th>Ref. No</th>
                        <th>Date</th>
                        <th>Quantity</th>
                        <th>Total Unit Adjusted</th>
                        <th>Unit Purchase Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->sub_sku }}</td>
                        <td>{{ $item->supplier }}</td>
                        <td>{{ $item->ref_no }}</td>
                        <td>{{ $item->transaction_date }}</td>
                        <td>{{ $item->purchase_qty }}</td>
                        <td>{{ $item->quantity_adjusted }}</td>
                        <td>{{ number_format($item->unit_purchase_price, 3) }} SAR</td>
                        <td>{{ number_format($item->subtotal, 3) }} SAR</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13">
                            No Data Available
                        </td>
                    </tr>
                    @endforelse
                    <tr class="total">
                        <td colspan="6">Total:</td>
                        <td>{{ number_format(collect($report)->sum('quantity_adjusted'), 3) }} SAR</td>
                        <td colspan="2"></td>
                        <td>{{ number_format(collect($report)->sum('subtotal'), 3) }} SAR</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
