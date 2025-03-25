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

        .header-box {
            margin-left: 20px;
        }

        .box {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8fafc;
        }

        .box table {
            margin: 10px;
            border: none;
            width: 100%;
            border-collapse: collapse;
        }

        .box th,
        .box td {
            border: none;
            text-align: left;
            padding: 10px;
        }

        .label {
            font-weight: bold;
            font-size: 12px
        }

        .value{
            font-size: 11px;
        }
        .data table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .data th,
        .data td {
            border: 0.5px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .data th {
            background-color: #083cb4;
            color: white;
            font-weight: bold;
        }

        .data tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .total {
            background-color: #c8c9ca;
        }

        .bold {
            font-weight: bold;
        }
        .rtl {
            direction: rtl;
        }
        .ltr {
            direction: ltr;
        }
    </style>
</head>
<body>
    @php
        if ($data->attachment_lang === 'ar') {
            \App::setLocale('ar');
        } else {
            \App::setLocale('en');
        }
    @endphp
    <div class="header">
        <img class="logo" src="{{ $logo }}" alt="logo">
        <h1>Ajyal Al - Madina Al - Asria</h1>
        <span>{{ env('APP_TITLE') }}</span>

        {{ Log::info('CUSTOMER & SUPPLIER -------------------------------------------------->') }}
        {{ Log::info(json_encode($report, JSON_PRETTY_PRINT)) }}
    </div>

    <div class="report-title">
        {{ __("report_type.$data->type") }}
    </div>

    <p class="date {{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
        {{ __('attachment.general.daterange', ['start' => $dates['start_date'], 'end' => $dates['end_date']]) }}
    </p>

    <div class="box">
        <table class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            <tr>
                <td>
                    <div class="label">{{ __('attachment.stock.th_closing_by_purchase') }}</div>
                    <div class="value">{{ number_format($report['stock_value']['closing_stock_by_pp'], 3) }} {{ $currency }}</div>
                </td>
                <td>
                    <div class="label">{{ __('attachment.stock.th_closing_by_sale') }}</div>
                    <div class="value">{{ number_format($report['stock_value']['closing_stock_by_sp'], 3) }} {{ $currency }}</div>
                </td>
                <td>
                    <div class="label">{{ __('attachment.stock.th_potential') }}</div>
                    <div class="value">{{ number_format($report['stock_value']['potential_profit'], 3) }} {{ $currency }}</div>
                </td>
                <td>
                    <div class="label">{{ __('attachment.stock.th_margin') }}</div>
                    <div class="value">{{ $report['stock_value']['profit_margin'] }} %</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="data">
        <table class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            <thead>
                <tr>
                    <th rowspan="2" class="indexing">#</th>
                    <th>{{ __('attachment.stock.th_sku') }}</th>
                    <th>{{ __('attachment.stock.th_product') }}</th>
                    <th>{{ __('attachment.stock.th_variations') }}</th>
                    <th>{{ __('attachment.stock.th_cat') }}</th>
                    <th>{{ __('attachment.stock.th_location') }}</th>
                    <th>{{ __('attachment.stock.th_unit_selling_price') }}</th>
                    <th>{{ __('attachment.stock.th_current_stock') }}</th>
                    <th>{{ __('attachment.stock.th_current_stock_by_purchase') }}</th>
                    <th>{{ __('attachment.stock.th_current_stock_by_sale') }}</th>
                </tr>
                <tr>
                    <th>{{ __('attachment.stock.th_potential') }}</th>
                    <th>{{ __('attachment.stock.th_tunit_sold') }}</th>
                    <th>{{ __('attachment.stock.th_tunit_transfered') }}</th>
                    <th>{{ __('attachment.stock.th_tunit_adjusted') }}</th>
                    <th>{{ __('attachment.stock.th_cust1') }}</th>
                    <th>{{ __('attachment.stock.th_cust2') }}</th>
                    <th>{{ __('attachment.stock.th_cust3') }}</th>
                    <th>{{ __('attachment.stock.th_cust4') }}</th>
                    <th>{{ __('attachment.stock.th_current_stock_manufacturing') }}</th>
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
                        <td>{{ number_format($item->unit_price, 3)  ?: '0' }} {{ $currency }}</td>
                        <td>{{ number_format($item->stock, 3) ?: '0' }} {{ __('attachment.stock.bags') }}</td>
                        <td>{{ number_format($item->stock_price,3)  ?: '0' }} {{ $currency }}</td>
                        <td>{{ number_format($stock_price_by_sp, 3)  ?: '0' }} {{ $currency }}</td>
                    </tr>
                    <tr>
                        <td>{{ number_format($potential_profit, 3) ?: '0' }} {{ $currency }}</td>
                        <td>{{ $item->total_sold ?: '-' }} {{ __('attachment.stock.bags') }}</td>
                        <td>{{ $item->total_transfered ?: '-' }} {{ __('attachment.stock.bags') }}</td>
                        <td>{{ $item->total_adjusted ?: '-' }} {{ __('attachment.stock.bags') }}</td>
                        <td>{{ $item->product_custom_field1 ?: '-' }}</td>
                        <td>{{ $item->product_custom_field2 ?: '-' }}</td>
                        <td>{{ $item->product_custom_field3 ?: '-' }}</td>
                        <td>{{ $item->product_custom_field4 ?: '-' }}</td>
                        <td>{{ number_format($item->total_mfg_stock, 3) ?: '0' }} {{ __('attachment.stock.bags') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
