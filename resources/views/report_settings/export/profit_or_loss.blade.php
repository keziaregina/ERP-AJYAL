<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profit / Loss Report</title>

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

        .date {
            font-size: 11px;
        }

        .indexing {
            width: 40px;
        }

        .label {
            font-size: 16px;
        }

        .overall {
            margin-top: 20px;
            background-color: #acb6d1;
            border-radius: 8px;
            padding: 20px;
        }

        .overall h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #444;
        }

        .overall p {
            font-size: 14px;
            color: #444;
        }

        .overall h3 {
            font-size: 16px;
            margin: 0 0 0 0;
            color: #444;
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

        .separator {
            border: none !important;
            background-color: #ffffff !important;
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
        Profit / Loss Report
    </div>

    <p class="date">
        Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
    </p>

    @php
        $left = $report['left_side_module_data'];
        $right = $report['right_side_module_data'];
        $maxCount = max(count($left), count($right));
    @endphp
    <div>
        <table>
            <thead>
                <tr>
                    <th colspan="2">Purchases</th>
                    <th class="separator"></th>
                    <th colspan="2">Sales</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Opening Stock
                        <br>
                        (By purchase price)
                    </td>
                    <td>{{ number_format($report['opening_stock'], 3) ?: '0' }} {{ $currency }}</td>
                    <td class="separator"></td>
                    <td>
                        Closing Stock
                        <br>
                        (By purchase price)
                    </td>
                    <td>{{ number_format($report['closing_stock'], 3) ?: '0' }} {{ $currency }}</td>

                </tr>
                <tr>
                    <td>
                        Opening Stock
                        <br>    
                        (By sale price)
                    </td>
                    <td>
                        @if (isset($report['opening_stock_by_sp']))
                        {{ number_format($report['opening_stock_by_sp'], 3) ?: '0' }} {{ $currency }}
                        @else
                            0 {{ $currency }}  
                        @endif
                    </td>
                    <td class="separator"></td>
                    <td>
                        Closing Stock
                        <br>    
                        (By sale price)
                    </td>
                    <td>
                        @if (isset($data['closing_stock_by_sp']))
                        {{ number_format($data['closing_stock_by_sp'], 3) ?: '0' }} {{ $currency }}
                        @else
                            0 {{ $currency }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                        Total Purchase
                        <br>    
                        (Exc. tax, Discount)
                    </td>
                    <td>
                        {{ number_format($report['total_purchase'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td class="separator"></td>
                    <td>
                        Total Purchase
                        <br>    
                        (Exc. tax, Discount)
                    </td>
                    <td>
                        {{ number_format($data['total_sell_by_subtype'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Total Stock Adjustment
                    </td>
                    <td>
                        {{ number_format($report['total_adjustment'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td class="separator"></td>
                    <td>
                        Total sell shipping charge
                    </td>
                    <td>
                        {{ number_format($report['total_sell_shipping_charge'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Total Expense
                    </td>
                    <td>
                        {{ number_format($report['total_expense'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td class="separator"></td>
                    <td>
                        Total Sell additional expenses
                    </td>
                    <td>
                        {{ number_format($report['total_sell_additional_expense'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Total purchase shipping charge
                    </td>
                    <td>
                        {{ number_format($report['total_purchase_shipping_charge'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td class="separator"></td>
                    <td>
                        Total Stock Recovered
                    </td>
                    <td>
                        {{ number_format($report['total_recovered'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Purchase additional expenses
                    </td>
                    <td>
                        {{ number_format($report['total_purchase_additional_expense'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td class="separator"></td>
                    <td>
                        Total Purchase Return
                    </td>
                    <td>
                        {{ number_format($report['total_purchase_return'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Total transfer shipping charge
                    </td>
                    <td>
                        {{ number_format($report['total_transfer_shipping_charges'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td class="separator"></td>
                    <td>
                        Total sell round off
                    </td>
                    <td>
                        {{ number_format($report['total_sell_round_off'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Total Sell discount
                    </td>
                    <td>
                        {{ number_format($report['total_sell_discount'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Total customer reward
                    </td>
                    <td>
                        {{ number_format($report['total_reward_amount'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td colspan="3" class="separator"></td>
                </tr>
                <tr>
                    <td>
                        Total sell return
                    </td>
                    <td>
                        {{ number_format($report['total_sell_return'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                @for ($i = 0; $i < $maxCount; $i++)
                <tr>
                    <td>{{ $left[$i]['label'] ?? '-' }}</td>
                    <td>{{ number_format($left[$i]['value'] ?? 0, 3) }} {{ $currency }}</td>
                    <td class="separator"></td>
                    <td>{{ $right[$i]['label'] ?? '-' }}</td>
                    <td>{{ number_format($right[$i]['value'] ?? 0, 3) }} {{ $currency }}</td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div class="overall">
        <h3>COGS:
            {{ number_format($report['opening_stock'] - $report['total_purchase'] + $report['closing_stock'], 3) }}
            SAR</h3>
        <p>
            Cost of Goods Sold = Starting inventory(opening stock) + purchases − ending inventory(closing stock)
        </p>

        <h3>Gross Profit: {{ number_format($report['gross_profit'], 3) }} SAR</h3>
        <p>
            (Total sell price - Total purchase price)
            @if (!empty($report['gross_profit_label']))
                {{-- + {{$data['gross_profit_label']}} --}}
                @foreach ($report['gross_profit_label'] as $val)
                    + {{ $val }}
                @endforeach
            @endif
        </p>
        <h3>Net Profit: {{ number_format($report['net_profit'], 3) }} SAR</h3>
        <p>
            Gross Profit + (Total sell shipping charge + Sell additional expenses + Total Stock Recovered + Total
            Purchase discount +
            Total sell round off )
            - ( Total Stock Adjustment + Total Expense + Total purchase shipping charge + Total transfer shipping charge
            + Purchase
            additional expenses + Total Sell discount + Total customer reward + Total Payroll + Total Production Cost )
        </p>
        {{-- <small class="help-block">@lang('lang_v1.gross_profit') + (@lang('lang_v1.total_sell_shipping_charge') + @lang('lang_v1.sell_additional_expense') + @lang('report.total_stock_recovered') + @lang('lang_v1.total_purchase_discount') + @lang('lang_v1.total_sell_round_off') 
        @foreach ($report['right_side_module_data'] as $module_data)
            @if (!empty($module_data['add_to_net_profit']))
                + {{$module_data['label']}} 
            @endif
        @endforeach
        ) <br> - ( @lang('report.total_stock_adjustment') + @lang('report.total_expense') + @lang('lang_v1.total_purchase_shipping_charge') + @lang('lang_v1.total_transfer_shipping_charge') + @lang('lang_v1.purchase_additional_expense') + @lang('lang_v1.total_sell_discount') + @lang('lang_v1.total_reward_amount') 
        @foreach ($report['left_side_module_data'] as $module_data)
            @if (!empty($module_data['add_to_net_profit']))
                + {{$module_data['label']}}
            @endif 
        @endforeach )</small> --}}
    </div>
</body>

</html>
