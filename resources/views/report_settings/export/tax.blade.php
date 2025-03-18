<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .table-container {
            width: 100%;
            border-collapse: collapse;
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
    </style>
</head>
<body>

    {{-- {{ Log::info("report --->") }}
    {{ Log::info(json_encode($report,JSON_PRETTY_PRINT)) }}

    {{ Log::info("data --->") }}
    {{ Log::info(json_encode($data,JSON_PRETTY_PRINT)) }}

    @dd($data) --}}
    <div class="report-title">
        Tax Report - AJYAL AL-MADINA AL ASRIA
    </div>

    <table>

        <thead>
            <tr>
                <th>Date</th>
                <th>Reference No</th>
                <th>Supplier</th>
                <th>Tax Number</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Discount</th>
                <th>VAT</th>
                <th>Total VAT</th>
            </tr>
        </thead>

        <tbody>
            {{-- Sample Data --}}
            <tr>
                <td>2024-03-18</td>
                <td>INV-12345</td>
                <td>Supplier A</td>
                <td>123456789</td>
                <td>1,500.00</td>
                <td>Bank Transfer</td>
                <td>50.00</td>
                <td>150.00</td>
                <td>1,650.00</td>
            </tr>
            
            {{-- Total Row --}}
            <tr class="total-row">
                <td colspan="4">Total:</td>
                <td>1,500.00</td>
                <td></td>
                <td>50.00</td>
                <td>150.00</td>
                <td>1,650.00</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
