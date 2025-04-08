<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Report</title>

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

        $colvis = json_decode(Cache::get('colvisState_register_report'), true) ?? [];
        $colCount = 1;

        foreach (range(0, 17) as $i) {
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

    <table style="margin-top: 10px" class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
        <thead>
            <tr>
                <th class="indexing">#</th>
                @if (!isset($colvis[0]) || $colvis[0] !== false)
                    <th>{{ __('attachment.register.th_open') }}</th>
                @endif
                @if (!isset($colvis[1]) || $colvis[1] !== false)
                    <th>{{ __('attachment.register.th_close') }}</th>
                @endif
                @if (!isset($colvis[2]) || $colvis[2] !== false)
                    <th>{{ __('attachment.register.th_location') }}</th>
                @endif
                @if (!isset($colvis[3]) || $colvis[3] !== false)
                    <th>{{ __('attachment.register.th_user') }}</th>
                @endif
                @if (!isset($colvis[4]) || $colvis[4] !== false)
                    <th>{{ __('attachment.register.th_card') }}</th>
                @endif
                @if (!isset($colvis[5]) || $colvis[5] !== false)
                    <th>{{ __('attachment.register.th_cheques') }}</th>
                @endif
                @if (!isset($colvis[6]) || $colvis[6] !== false)
                    <th>{{ __('attachment.register.th_cash') }}</th>
                @endif
                @if (!isset($colvis[7]) || $colvis[7] !== false)
                    <th>{{ __('attachment.register.th_bank') }}</th>
                @endif
                @if (!isset($colvis[8]) || $colvis[8] !== false)
                    <th>{{ __('attachment.register.th_advance') }}</th>
                @endif
                @if (!isset($colvis[9]) || $colvis[9] !== false)
                    <th>{{ __('attachment.register.th_cust_payment1') }}</th>
                @endif
                @if (!isset($colvis[10]) || $colvis[10] !== false)
                    <th>{{ __('attachment.register.th_cust_payment2') }}</th>
                @endif
                @if (!isset($colvis[11]) || $colvis[11] !== false)
                    <th>{{ __('attachment.register.th_cust_payment3') }}</th>
                @endif
                @if (!isset($colvis[12]) || $colvis[12] !== false)
                    <th>{{ __('attachment.register.th_cust_payment4') }}</th>
                @endif
                @if (!isset($colvis[13]) || $colvis[13] !== false)
                    <th>{{ __('attachment.register.th_cust_payment5') }}</th>
                @endif
                @if (!isset($colvis[14]) || $colvis[14] !== false)
                    <th>{{ __('attachment.register.th_cust_payment6') }}</th>
                @endif
                @if (!isset($colvis[15]) || $colvis[15] !== false)
                    <th>{{ __('attachment.register.th_cust_payment7') }}</th>
                @endif
                @if (!isset($colvis[16]) || $colvis[16] !== false)
                    <th>{{ __('attachment.register.th_other_payment') }}</th>
                @endif
                @if (!isset($colvis[17]) || $colvis[17] !== false)
                    <th>{{ __('attachment.register.th_total') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $index => $item)
                @php
                    $total =
                        $item->total_card_payment +
                        $item->total_cheque_payment +
                        $item->total_cash_payment +
                        $item->total_bank_transfer_payment +
                        $item->total_other_payment +
                        $item->total_advance_payment +
                        $item->total_custom_pay_1 +
                        $item->total_custom_pay_2 +
                        $item->total_custom_pay_3 +
                        $item->total_custom_pay_4 +
                        $item->total_custom_pay_5 +
                        $item->total_custom_pay_6 +
                        $item->total_custom_pay_7;
                @endphp

                <tr>
                    <td rowspan="2">{{ $index + 1 }}</td>
                    @if (!isset($colvis[0]) || $colvis[0] !== false)
                        <td>-</td>
                    @endif
                    @if (!isset($colvis[1]) || $colvis[1] !== false)
                        <td>{{ $item->status === 'close' ? $item->closed_at : '-' }}</td>
                    @endif
                    @if (!isset($colvis[2]) || $colvis[2] !== false)
                        <td>{{ $item->location_name }}</td>
                    @endif
                    @if (!isset($colvis[3]) || $colvis[3] !== false)
                        <td>{!! $item->user_name !!}</td>
                    @endif
                    @if (!isset($colvis[4]) || $colvis[4] !== false)
                        <td>{{ number_format($item->total_card_payment ?: '0', 3) }}</td>
                    @endif
                    @if (!isset($colvis[5]) || $colvis[5] !== false)
                        <td>{{ number_format($item->total_cheque_payment ?: '0', 3) }}</td>
                    @endif
                    @if (!isset($colvis[6]) || $colvis[6] !== false)
                        <td>{{ number_format($item->total_cash_payment ?: '0', 3) }}</td>
                    @endif
                    @if (!isset($colvis[7]) || $colvis[7] !== false)
                        <td>{{ number_format($item->total_bank_transfer_payment ?: '0', 3) }}</td>
                    @endif
                    @if (!isset($colvis[8]) || $colvis[8] !== false)
                        <td>{{ number_format($item->total_advance_payment ?: '0', 3) }}</td>
                    @endif
                    @if (!isset($colvis[9]) || $colvis[9] !== false)
                        <td>{{ number_format($item->total_custom_pay_1 ?: '0') }}</td>
                    @endif
                    @if (!isset($colvis[10]) || $colvis[10] !== false)
                        <td>{{ number_format($item->total_custom_pay_2 ?: '0') }}</td>
                    @endif
                    @if (!isset($colvis[11]) || $colvis[11] !== false)
                        <td>{{ number_format($item->total_custom_pay_3 ?: '0') }}</td>
                    @endif
                    @if (!isset($colvis[12]) || $colvis[12] !== false)
                        <td>{{ number_format($item->total_custom_pay_4 ?: '0') }}</td>
                    @endif
                    @if (!isset($colvis[13]) || $colvis[13] !== false)
                        <td>{{ number_format($item->total_custom_pay_5 ?: '0') }}</td>
                    @endif
                    @if (!isset($colvis[14]) || $colvis[14] !== false)
                        <td>{{ number_format($item->total_custom_pay_6 ?: '0') }}</td>
                    @endif
                    @if (!isset($colvis[15]) || $colvis[15] !== false)
                        <td>{{ number_format($item->total_custom_pay_7 ?: '0') }}</td>
                    @endif
                    @if (!isset($colvis[16]) || $colvis[16] !== false)
                        <td>{{ number_format($item->total_other_payment ?: '0') }}</td>
                    @endif
                    @if (!isset($colvis[17]) || $colvis[17] !== false)
                        <td>{{ number_format($total ?: '0') }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan={{ $colCount }}>{{ __('attachment.general.empty') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <table>
        <tr class="total">
            @if (!isset($colvis[4]) || $colvis[4] !== false)
                <td class="bold">{{ __('attachment.register.tf_card') }}</td>
            @endif
            @if (!isset($colvis[5]) || $colvis[5] !== false)
                <td class="bold">{{ __('attachment.register.tf_cheques') }}</td>
            @endif
            @if (!isset($colvis[6]) || $colvis[6] !== false)
                <td class="bold">{{ __('attachment.register.tf_cash') }}</td>
            @endif
            @if (!isset($colvis[7]) || $colvis[7] !== false)
                <td class="bold">{{ __('attachment.register.tf_bank') }}</td>
            @endif
            @if (!isset($colvis[8]) || $colvis[8] !== false)
                <td class="bold">{{ __('attachment.register.tf_advance') }}</td>
            @endif
            @if (!isset($colvis[9]) || $colvis[9] !== false)
                <td class="bold">{{ __('attachment.register.tf_cust_payment1') }}</td>
            @endif
            @if (!isset($colvis[10]) || $colvis[10] !== false)
                <td class="bold">{{ __('attachment.register.tf_cust_payment2') }}</td>
            @endif
            @if (!isset($colvis[11]) || $colvis[11] !== false)
                <td class="bold">{{ __('attachment.register.tf_cust_payment3') }}</td>
            @endif
            @if (!isset($colvis[12]) || $colvis[12] !== false)
                <td class="bold">{{ __('attachment.register.tf_cust_payment4') }}</td>
            @endif
            @if (!isset($colvis[13]) || $colvis[13] !== false)
                <td class="bold">{{ __('attachment.register.tf_cust_payment5') }}</td>
            @endif
            @if (!isset($colvis[14]) || $colvis[14] !== false)
                <td class="bold">{{ __('attachment.register.tf_cust_payment6') }}</td>
            @endif
            @if (!isset($colvis[15]) || $colvis[15] !== false)
                <td class="bold">{{ __('attachment.register.tf_cust_payment7') }}</td>
            @endif
            @if (!isset($colvis[17]) || $colvis[17] !== false)
                <td class="bold">{{ __('attachment.register.tf_subtotal') }}</td>
            @endif
        </tr>
        <tr>
            @if (!isset($colvis[4]) || $colvis[4] !== false)
                <td>{{ number_format(collect($report)->sum('total_card_payment') ?: '0', 3) }} {{ $currency }}</td>
            @endif
            @if (!isset($colvis[5]) || $colvis[5] !== false)
                <td>{{ number_format(collect($report)->sum('total_cheque_payment') ?: '0', 3) }} {{ $currency }}</td>
            @endif
            @if (!isset($colvis[6]) || $colvis[6] !== false)
                <td>{{ number_format(collect($report)->sum('total_cash_payment') ?: '0', 3) }} {{ $currency }}</td>
            @endif
            @if (!isset($colvis[7]) || $colvis[7] !== false)
                <td>{{ number_format(collect($report)->sum('total_bank_transfer_payment') ?: '0', 3) }} {{ $currency }}</td>
            @endif
            @if (!isset($colvis[8]) || $colvis[8] !== false)
                <td>{{ number_format(collect($report)->sum('total_advance_payment') ?: '0', 3) }}{{ $currency }}</td>
            @endif
            @if (!isset($colvis[9]) || $colvis[9] !== false)
                <td>{{ number_format(collect($report)->sum('total_custom_pay_1') ?: '0', 3) }} {{ $currency }}</td>
            @endif
            @if (!isset($colvis[10]) || $colvis[10] !== false)
                <td>{{ number_format(collect($report)->sum('total_custom_pay_2') ?: '0', 3) }} {{ $currency }}</td>
            @endif
            @if (!isset($colvis[11]) || $colvis[11] !== false)
                <td>{{ number_format(collect($report)->sum('total_custom_pay_3') ?: '0', 3) }} {{ $currency }}</td>
            @endif
            @if (!isset($colvis[12]) || $colvis[12] !== false)
                <td>{{ number_format(collect($report)->sum('total_custom_pay_4') ?: '0', 3) }} {{ $currency }}</td>
            @endif
            @if (!isset($colvis[13]) || $colvis[13] !== false)
                <td>{{ number_format(collect($report)->sum('total_custom_pay_5') ?: '0', 3) }} {{ $currency }}</td>
            @endif
            @if (!isset($colvis[14]) || $colvis[14] !== false)
                <td>{{ number_format(collect($report)->sum('total_custom_pay_6') ?: '0', 3) }} {{ $currency }}</td>
            @endif
            @if (!isset($colvis[15]) || $colvis[15] !== false)
                <td>{{ number_format(collect($report)->sum('total_custom_pay_7') ?: '0', 3) }} {{ $currency }}</td>
            @endif
            @if (!isset($colvis[17]) || $colvis[17] !== false)
                <td>{{ number_format(collect($report)->sum('total') ?: '0', 3) }} {{ $currency }}</td>
            @endif
        </tr>
    </table>
</body>

</html>
