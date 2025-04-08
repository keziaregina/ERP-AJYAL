<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stock Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 15px;
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
            font-size: 11px;
        }

        .data th,
        .data td {
            border: 0.5px solid #ddd;
            padding: 6px 4px;
            text-align: center;
            width: auto;
        }

        tr .indexing, td .indexing {
            width: 40px !important;
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

        $colvis = json_decode(Cache::get('colvisState_stock_report'), true) ?? [];
        $colCount = 1;

        foreach (range(1, 18) as $i) {
            if (!isset($colvis[$i]) || $colvis[$i] !== false) {
                $colCount++;
            }
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
        <table style="margin-top: 10px" class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            <thead>
                <tr>
                    <th class="indexing">#</th>
                    @if (!isset($colvis[1]) || $colvis[1] !== false)
                        <th>{{ __('attachment.stock.th_sku') }}</th>
                    @endif
                    @if (!isset($colvis[2]) || $colvis[2] !== false)
                        <th>{{ __('attachment.stock.th_product') }}</th>
                    @endif
                    @if (!isset($colvis[3]) || $colvis[3] !== false)
                        <th>{{ __('attachment.stock.th_variations') }}</th>
                    @endif
                    @if (!isset($colvis[4]) || $colvis[4] !== false)
                        <th>{{ __('attachment.stock.th_cat') }}</th>
                    @endif
                    @if (!isset($colvis[5]) || $colvis[5] !== false)
                        <th>{{ __('attachment.stock.th_location') }}</th>
                    @endif
                    @if (!isset($colvis[6]) || $colvis[6] !== false)
                        <th>{{ __('attachment.stock.th_unit_selling_price') }}</th>
                    @endif
                    @if (!isset($colvis[7]) || $colvis[7] !== false)
                        <th>{{ __('attachment.stock.th_current_stock') }}</th>
                    @endif
                    @if (!isset($colvis[8]) || $colvis[8] !== false)
                        <th>{{ __('attachment.stock.th_current_stock_by_purchase') }}</th>
                    @endif
                    @if (!isset($colvis[9]) || $colvis[9] !== false)
                        <th>{{ __('attachment.stock.th_current_stock_by_sale') }}</th>
                    @endif
                    @if (!isset($colvis[10]) || $colvis[10] !== false)
                        <th>{{ __('attachment.stock.th_potential') }}</th>
                    @endif
                    @if (!isset($colvis[11]) || $colvis[11] !== false)
                        <th>{{ __('attachment.stock.th_tunit_sold') }}</th>
                    @endif
                    @if (!isset($colvis[12]) || $colvis[12] !== false)
                        <th>{{ __('attachment.stock.th_tunit_transfered') }}</th>
                    @endif
                    @if (!isset($colvis[13]) || $colvis[13] !== false)
                        <th>{{ __('attachment.stock.th_tunit_adjusted') }}</th>
                    @endif
                    @if (!isset($colvis[14]) || $colvis[14] !== false)
                        <th>{{ __('attachment.stock.th_cust1') }}</th>
                    @endif
                    @if (!isset($colvis[15]) || $colvis[15] !== false)
                        <th>{{ __('attachment.stock.th_cust2') }}</th>
                    @endif
                    @if (!isset($colvis[16]) || $colvis[16] !== false)
                        <th>{{ __('attachment.stock.th_cust3') }}</th>
                    @endif
                    @if (!isset($colvis[17]) || $colvis[17] !== false)
                        <th>{{ __('attachment.stock.th_cust4') }}</th>
                    @endif
                    @if (!isset($colvis[18]) || $colvis[18] !== false)
                        <th>{{ __('attachment.stock.th_current_stock_manufacturing') }}</th>
                    @endif
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
                        <td class="indexing">{{ $index + 1 }}</td>
                        @if (!isset($colvis[1]) || $colvis[1] !== false)
                            <td>{{ $item->sku }}</td>
                        @endif
                        @if (!isset($colvis[2]) || $colvis[2] !== false)
                            <td>{{ $item->product }}</td>
                        @endif
                        @if (!isset($colvis[3]) || $colvis[3] !== false)
                            <td>{{ $item->variation_name ?: '-' }}</td>
                        @endif
                        @if (!isset($colvis[4]) || $colvis[4] !== false)
                            <td>{{ $item->category_name ?: '-' }}</td>
                        @endif
                        @if (!isset($colvis[5]) || $colvis[5] !== false)
                            <td>{{ $item->location_name ?: '-' }}</td>
                        @endif
                        @if (!isset($colvis[6]) || $colvis[6] !== false)
                            <td>{{ number_format($item->unit_price, 3)  ?: '0' }} {{ $currency }}</td>
                        @endif
                        @if (!isset($colvis[7]) || $colvis[7] !== false)
                            <td>{{ number_format($item->stock, 3) ?: '0' }} {{ __('attachment.stock.bags') }}</td>
                        @endif
                        @if (!isset($colvis[8]) || $colvis[8] !== false)
                            <td>{{ number_format($item->stock_price,3)  ?: '0' }} {{ $currency }}</td>
                        @endif
                        @if (!isset($colvis[9]) || $colvis[9] !== false)
                            <td>{{ number_format($stock_price_by_sp, 3)  ?: '0' }} {{ $currency }}</td>
                        @endif
                        @if (!isset($colvis[10]) || $colvis[10] !== false)
                            <td>{{ number_format($potential_profit, 3) ?: '0' }} {{ $currency }}</td>
                        @endif
                        @if (!isset($colvis[11]) || $colvis[11] !== false)
                            <td>{{ $item->total_sold ?: '-' }} {{ __('attachment.stock.bags') }}</td>
                        @endif
                        @if (!isset($colvis[12]) || $colvis[12] !== false)
                            <td>{{ $item->total_transfered ?: '-' }} {{ __('attachment.stock.bags') }}</td>
                        @endif
                        @if (!isset($colvis[13]) || $colvis[13] !== false)
                            <td>{{ $item->total_adjusted ?: '-' }} {{ __('attachment.stock.bags') }}</td>
                        @endif
                        @if (!isset($colvis[14]) || $colvis[14] !== false)
                            <td>{{ $item->product_custom_field1 ?: '-' }}</td>
                        @endif
                        @if (!isset($colvis[15]) || $colvis[15] !== false)
                            <td>{{ $item->product_custom_field2 ?: '-' }}</td>
                        @endif
                        @if (!isset($colvis[16]) || $colvis[16] !== false)
                            <td>{{ $item->product_custom_field3 ?: '-' }}</td>
                        @endif
                        @if (!isset($colvis[17]) || $colvis[17] !== false)
                            <td>{{ $item->product_custom_field4 ?: '-' }}</td>
                        @endif
                        @if (!isset($colvis[18]) || $colvis[18] !== false)
                            <td>{{ number_format($item->total_mfg_stock, 3) ?: '0' }} {{ __('attachment.stock.bags') }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
