<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Sell Summary</title>
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

        th .indexing, td .indexing {
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

        $colvis = json_decode(Cache::get('colvisState_table#product_sell_report_table'), true) ?? [];
        $colCount = 1;

        foreach (range(0, 13) as $i) {
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

    <table style="margin-top: 10px;" class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
        <thead>
            <tr>
                <th>#</th>
                @if (!isset($colvis[0]) || $colvis[0] !== false)
                    <th>{{ __('attachment.product_sell.th_product') }}</th>
                @endif
                @if (!isset($colvis[1]) || $colvis[1] !== false)
                    <th>{{ __('attachment.product_sell.th_sku') }}</th>
                @endif
                @if (!isset($colvis[2]) || $colvis[2] !== false)
                    <th></th>
                @endif
                @if (!isset($colvis[3]) || $colvis[3] !== false)
                    <th></th>
                @endif
                @if (!isset($colvis[4]) || $colvis[4] !== false)
                    <th>{{ __('attachment.product_sell.th_customer_name') }}</th>
                @endif
                @if (!isset($colvis[5]) || $colvis[5] !== false)
                    <th>{{ __('attachment.product_sell.th_contact_id') }}</th>
                @endif
                @if (!isset($colvis[6]) || $colvis[6] !== false)
                    <th>{{ __('attachment.product_sell.th_invoice_no') }}</th>
                @endif
                @if (!isset($colvis[7]) || $colvis[7] !== false)
                    <th>{{ __('attachment.product_sell.th_date') }}</th>
                @endif
                @if (!isset($colvis[8]) || $colvis[8] !== false)
                    <th>{{ __('attachment.product_sell.th_unit_price') }}</th>
                @endif
                @if (!isset($colvis[9]) || $colvis[9] !== false)
                    <th>{{ __('attachment.product_sell.th_discount') }}</th>
                @endif
                @if (!isset($colvis[10]) || $colvis[10] !== false)
                    <th>{{ __('attachment.product_sell.th_tax') }}</th>
                @endif
                @if (!isset($colvis[11]) || $colvis[11] !== false)
                    <th>{{ __('attachment.product_sell.th_price_inctax') }}</th>
                @endif
                @if (!isset($colvis[12]) || $colvis[12] !== false)
                    <th>{{ __('attachment.product_sell.th_total') }}</th>
                @endif
                @if (!isset($colvis[13]) || $colvis[13] !== false)
                    <th>{{ __('attachment.product_sell.th_payment_method') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                @if (!isset($colvis[0]) || $colvis[0] !== false)
                    <td>{{ $item->product_name ?: '-' }}</td>
                @endif
                @if (!isset($colvis[1]) || $colvis[1] !== false)
                    <td>{{ $item->sub_sku ?: '-' }}</td>
                @endif
                @if (!isset($colvis[2]) || $colvis[2] !== false)
                    <td></td>
                @endif
                @if (!isset($colvis[3]) || $colvis[3] !== false)
                    <td></td>
                @endif
                @if (!isset($colvis[4]) || $colvis[4] !== false)
                    <td>{{ $item->customer ?: '-' }}</td>
                @endif
                @if (!isset($colvis[5]) || $colvis[5] !== false)
                    <td>{{ $item->contact_id ?: '-' }}</td>
                @endif
                @if (!isset($colvis[6]) || $colvis[6] !== false)
                    <td>{{ $item->invoice_no ?: '-' }}</td>
                @endif
                @if (!isset($colvis[7]) || $colvis[7] !== false)
                    <td>{{ $item->transaction_date ?: '-' }}</td>
                @endif
                @if (!isset($colvis[8]) || $colvis[8] !== false)
                    <td>{{ number_format($item->sell_qty, 3) ?: '0' }}</td>
                @endif
                @if (!isset($colvis[9]) || $colvis[9] !== false)
                    <td>{{ number_format($item->unit_price, 3) ?: '0' }}</td>
                @endif
                @if (!isset($colvis[10]) || $colvis[10] !== false)
                    <td>{{ number_format($item->discount_amount, 3) ?: '0' }}</td>
                @endif
                @if (!isset($colvis[11]) || $colvis[11] !== false)
                    <td>{{ number_format($item->tax, 3) ?: '0' }}</td>
                @endif
                @if (!isset($colvis[12]) || $colvis[12] !== false)
                    <td>{{ number_format($item->unit_sale_price, 3) ?: '0' }} {{ $currency }}</td>
                @endif
                @if (!isset($colvis[13]) || $colvis[13] !== false)
                    <td>{{ number_format($item->subtotal, 3) ?: '0' }} {{ $currency }}</td>
                @endif
                @if (!isset($colvis[14]) || $colvis[14] !== false)
                    <td>{{ $item->payment_methods ?: '-' }}</td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan={{ $colCount }}>
                    {{ __('attachment.general.empty') }}
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <table>
        @if (!isset($colvis[12]) || $colvis[12] !== false)
            <tr class="total">
                <td class="bold" colspan="12">{{ __('attachment.general.subtotal') }}</td>
            </tr>
            <tr>
                <td class="bold" colspan="12">{{ __('attachment.product_purchase.subtotal') }}</td>
            </tr>
            <tr>
                <td colspan="12">{{ number_format(collect($report)->sum('subtotal'), 3) ?: '0' }} {{ $currency }}</td>
            </tr>
        @endif
    </table>
</body>
</html>
