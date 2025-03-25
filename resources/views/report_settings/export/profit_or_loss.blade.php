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
            background-color: #083cb4;
            color: white;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
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

    @php
        $left = $report['left_side_module_data'];
        $right = $report['right_side_module_data'];
        $maxCount = max(count($left), count($right));
    @endphp
    <div>
        <table class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            <thead>
                <tr>
                    <th colspan="2">{{ __('attachment.profit_loss.th_purchase') }}</th>
                    <th class="separator"></th>
                    <th colspan="2">{{ __('attachment.profit_loss.th_sales') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {!! __('attachment.profit_loss.td_opening_by_pp') !!}
                    </td>
                    <td>{{ number_format($report['opening_stock'], 3) ?: '0' }} {{ $currency }}</td>
                    <td class="separator"></td>
                    <td>
                        {!! __('attachment.profit_loss.td_closing_by_pp') !!}
                    </td>
                    <td>{{ number_format($report['closing_stock'], 3) ?: '0' }} {{ $currency }}</td>

                </tr>
                <tr>
                    <td>
                        {!! __('attachment.profit_loss.td_opening_by_sp') !!}
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
                        {!! __('attachment.profit_loss.td_closing_by_sp') !!}
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
                        {!! __('attachment.profit_loss.td_tpurchase_exc_tax') !!}
                    </td>
                    <td>
                        {{ number_format($report['total_purchase'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td class="separator"></td>
                    <td>
                        {!! __('attachment.profit_loss.td_tsales_exc_tax') !!}
                    </td>
                    <td>
                        {{ number_format($data['total_sell_by_subtype'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('attachment.profit_loss.td_tstock_adjustment') }}
                    </td>
                    <td>
                        {{ number_format($report['total_adjustment'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td class="separator"></td>
                    <td>
                        {{ __('attachment.profit_loss.td_tsell_shipping') }}
                    </td>
                    <td>
                        {{ number_format($report['total_sell_shipping_charge'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('attachment.profit_loss.td_texpense') }}
                    </td>
                    <td>
                        {{ number_format($report['total_expense'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td class="separator"></td>
                    <td>
                        {{ __('attachment.profit_loss.td_tsell_additional_expenses') }}
                    </td>
                    <td>
                        {{ number_format($report['total_sell_additional_expense'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('attachment.profit_loss.td_tpurchase_shipping') }}
                    </td>
                    <td>
                        {{ number_format($report['total_purchase_shipping_charge'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td class="separator"></td>
                    <td>
                        {{ __('attachment.profit_loss.td_tstock_recovered') }}
                    </td>
                    <td>
                        {{ number_format($report['total_recovered'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                       {{ __('attachment.profit_loss.td_purchase_additional') }}
                    </td>
                    <td>
                        {{ number_format($report['total_purchase_additional_expense'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td class="separator"></td>
                    <td>
                        {{ __('attachment.profit_loss.td_tpurchase_return') }}
                    </td>
                    <td>
                        {{ number_format($report['total_purchase_return'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('attachment.profit_loss.td_ttransfer_shipping') }}
                    </td>
                    <td>
                        {{ number_format($report['total_transfer_shipping_charges'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td class="separator"></td>
                    <td>
                        {{ __('attachment.profit_loss.td_tsell_round_off') }}
                    </td>
                    <td>
                        {{ number_format($report['total_sell_round_off'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('attachment.profit_loss.td_tsell_discount') }}
                    </td>
                    <td>
                        {{ number_format($report['total_sell_discount'], 3) ?: '0' }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td>
                        {{ __('attachment.profit_loss.td_tcustomer_reward') }}
                    </td>
                    <td>
                        {{ number_format($report['total_reward_amount'], 3) ?: '0' }} {{ $currency }}
                    </td>
                    <td colspan="3" class="separator"></td>
                </tr>
                <tr>
                    <td>
                        {{ __('attachment.profit_loss.td_tsell_return') }}
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
    <div class="overall {{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
        <h3>{{ __('attachment.profit_loss.cogs') }}
            {{ number_format($report['opening_stock'] - $report['total_purchase'] + $report['closing_stock'], 3) }}
            {{ $currency }}</h3>
        <p>
            {{ __('attachment.profit_loss.cogs_desc') }}
        </p>

        <h3>{{ __('attachment.profit_loss.gross_profit') }} {{ number_format($report['gross_profit'], 3) }} {{ $currency }}</h3>
        <p>
            {{ __('attachment.profit_loss.tsell-tpurchase_price') }}
            @if (!empty($report['gross_profit_label']))
                {{-- + {{$data['gross_profit_label']}} --}}
                @foreach ($report['gross_profit_label'] as $val)
                    + {{ $val }}
                @endforeach
            @endif
        </p>
        <h3>{{ __('attachment.profit_loss.nett_profit') }} {{ number_format($report['net_profit'], 3) }} {{ $currency }}</h3>
        <p>
            {!! __('attachment.profit_loss.desc') !!}
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
