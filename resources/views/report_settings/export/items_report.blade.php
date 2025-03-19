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
                        <th>Purchase Date</th>
                        <th>Purchase</th>
                        <th>Supplier</th>
                        <th>Purchase Price</th>
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
                    <tr>
                        <td>ALAF AF ALBANAH</td>
                        <td>AB001</td>
                        <td>10-03-2025 10:06 AM</td>
                        <td>PO2025/0003</td>
                        <td class="arabic">البانة العالمية</td>
                        <td>2.300 ريال</td>
                        <td>10-03-2025 10:42 AM</td>
                        <td></td>
                        <td></td>
                        <td>MAIN BRANCH</td>
                        <td>1,000.000 Bundle حزمة</td>
                        <td>2.300 ريال</td>
                        <td>2,300.000 ريال</td>
                    </tr>
                    <tr>
                        <td>ALAF AF ALBANAH</td>
                        <td>AB001</td>
                        <td>10-03-2025 10:17 AM</td>
                        <td>00</td>
                        <td class="arabic">البانة العالمية</td>
                        <td>2.300 ريال</td>
                        <td>10-03-2025 10:42 AM</td>
                        <td></td>
                        <td></td>
                        <td>MAIN BRANCH</td>
                        <td>1,100.000 Bundle حزمة</td>
                        <td>2.300 ريال</td>
                        <td>2,530.000 ريال</td>
                    </tr>
                    <tr class="total">
                        <td colspan="10">Total:</td>
                        <td>2,100.000</td>
                        <td>4.600 ريال</td>
                        <td>4,830.000 ريال</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
