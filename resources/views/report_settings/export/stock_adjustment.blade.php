<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stock Adjustments Report</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        th,
        td {
            border: 0.5px solid #ddd;
            padding: 6px 4px;
            text-align: center;
            width: auto;
        }

        tr .indexing, td .indexing {
            width: 40px !important;
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
        .label {
            font-weight: bold;
            font-size: 12px
        }

        .title {
            font-size: 13px;
            font-weight: bold;
        }

        .value{
            font-size: 11px;
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

        $colvis = json_decode(Cache::get('colvisState_stock_adjustment'), true) ?? [];
        $colCount = 1;

        foreach (range(1, 8) as $i) {
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

    <div>
        <div class="box">
            <table class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
                <tr>
                    <td class="label">
                        {{ __('attachment.stock_adjustment.tbox_normal') }}
                    </td>
                    <td class="label">
                        {{ __('attachment.stock_adjustment.tbox_abnormal') }}
                    </td>
                    <td class="label">
                        {{ __('attachment.stock_adjustment.tbox_sa') }}
                    </td>
                    <td class="label">
                        {{ __('attachment.stock_adjustment.tbox_ar') }}
                    </td>
                </tr>
                <tr>
                    <td>{{ number_format($report['details']->total_normal ?: '0', 3) }} {{ $currency }}</h3>
                    <td>{{ number_format($report['details']->total_abnormal ?: '0', 3) }} {{ $currency }}</h3>
                    <td>{{ number_format($report['details']->total_amount ?: '0', 3) }} {{ $currency }}</h3>
                    <td>{{ number_format($report['details']->total_recovered ?: '0', 3) }} {{ $currency }}</h3>
                </tr>
                {{-- <tr>
                        <td>{{ number_format(($report['opening_stock'] - $report['total_purchase'] + $report['closing_stock']) ?: '0', 3) }} {{ $currency }}</h3>
                        <td>{{ number_format($report['gross_profit'] ?: '0', 3) }} {{ $currency }}</h3>
                        <td>{{ number_format($report['net_profit'] ?: '0', 3) }} {{ $currency }}</h3>
                    </tr> --}}
            </table>
        </div>

        <div>
            <h3 class="label">{{ __('attachment.stock_adjustment.sa') }}</h3>
            <table style="margin-top: 10px">
                <thead>
                    <tr>
                        <th class="indexing">#</th>
                        @if (!isset($colvis[1]) || $colvis[1] !== false)
                            <th>{{ __('attachment.stock_adjustment.th_date') }}</th>
                        @endif
                        @if (!isset($colvis[2]) || $colvis[2] !== false)
                            <th>{{ __('attachment.stock_adjustment.th_ref_no') }}</th>
                        @endif
                        @if (!isset($colvis[3]) || $colvis[3] !== false)
                            <th>{{ __('attachment.stock_adjustment.th_location') }}</th>
                        @endif
                        @if (!isset($colvis[4]) || $colvis[4] !== false)
                            <th>{{ __('attachment.stock_adjustment.th_type') }}</th>
                        @endif
                        @if (!isset($colvis[5]) || $colvis[5] !== false)
                            <th>{{ __('attachment.stock_adjustment.th_tamount') }}</th>
                        @endif
                        @if (!isset($colvis[6]) || $colvis[6] !== false)
                            <th>{{ __('attachment.stock_adjustment.th_tamount_recovered') }}</th>
                        @endif
                        @if (!isset($colvis[7]) || $colvis[7] !== false)
                            <th>{{ __('attachment.stock_adjustment.th_reason') }}</th>
                        @endif
                        @if (!isset($colvis[8]) || $colvis[8] !== false)
                            <th>{{ __('attachment.stock_adjustment.th_added_by') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report['collection'] as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</th>
                            @if (!isset($colvis[1]) || $colvis[1] !== false)
                                <td>{{ $item->transaction_date }}</th>
                            @endif
                            @if (!isset($colvis[2]) || $colvis[2] !== false)
                                <td>{{ $item->ref_no }}</th>
                            @endif
                            @if (!isset($colvis[3]) || $colvis[3] !== false)
                                <td>{{ $item->location_name }}</th>
                            @endif
                            @if (!isset($colvis[4]) || $colvis[4] !== false)
                                <td>{{ $item->adjustment_type }}</th>
                            @endif
                            @if (!isset($colvis[5]) || $colvis[5] !== false)
                                <td>{{ $item->final_total }}</th>
                            @endif
                            @if (!isset($colvis[6]) || $colvis[6] !== false)
                                <td>{{ $item->total_amount_recovered }}</th>
                            @endif
                            @if (!isset($colvis[7]) || $colvis[7] !== false)
                                <td>{{ $item->additional_notes }}</th>
                            @endif
                            @if (!isset($colvis[8]) || $colvis[8] !== false)
                                <td>{{ $item->added_by }}</th>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan={{ $colCount }} style="text-align: center;">{{ __('attachment.general.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
