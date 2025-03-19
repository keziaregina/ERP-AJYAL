<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Group Report</title>
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
    </style>
</head>
<body>

    <img class="logo" src="{{ $logo }}" alt="logo">
    <h1>Ajyal Al - Madina</h1>

    {{-- {{ Log::info("CUSTOMER & SUPPLIER -------------------------------------------------->") }}
    {{ Log::info(json_encode($report,JSON_PRETTY_PRINT)) }} --}}

    <div class="report-title">
        Customer Group Report - AJYAL AL-MADINA AL ASRIA
    </div>

    <table>
        <thead>
            <tr>
                <th>Customer Group</th>
                <th>Total Sale</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ number_format($item['total_sell'], 3) }} SAR</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No data found</td>
                </tr>
            @endforelse
            
        </tbody>
    </table>

</body>
</html>
