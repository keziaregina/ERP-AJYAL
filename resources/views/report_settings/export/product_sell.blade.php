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
        .indexing {
            width: 40px;
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

    <table class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
        <thead>
            <tr>
                <th rowspan="2">#</th>
                <th>{{ __('attachment.product_sell.th_product') }}</th>
                <th>{{ __('attachment.product_sell.th_sku') }}</th>
                <th>{{ __('attachment.product_sell.th_customer_name') }}</th>
                <th>{{ __('attachment.product_sell.th_contact_id') }}</th>
                <th>{{ __('attachment.product_sell.th_invoice_no') }}</th>
                <th>{{ __('attachment.product_sell.th_date') }}</th>
            </tr>
            <tr>
                <th>{{ __('attachment.product_sell.th_unit_price') }}</th>
                <th>{{ __('attachment.product_sell.th_discount') }}</th>
                <th>{{ __('attachment.product_sell.th_tax') }}</th>
                <th>{{ __('attachment.product_sell.th_price_inctax') }}</th>
                <th>{{ __('attachment.product_sell.th_total') }}</th>
                <th>{{ __('attachment.product_sell.th_payment_method') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $index => $item)
            <tr>
                <td rowspan="2">{{ $index + 1 }}</td>
                <td>{{ $item->product_name ?: '-' }}</td>
                <td>{{ $item->sub_sku ?: '-' }}</td>
                <td>{{ $item->customer ?: '-' }}</td>
                <td>{{ $item->contact_id ?: '-' }}</td>
                <td>{{ $item->invoice_no ?: '-' }}</td>
                <td>{{ $item->transaction_date ?: '-' }}</td>
            </tr>
            <tr>
                <td>{{ number_format($item->sell_qty, 3) ?: '0' }}</td>
                <td>{{ number_format($item->unit_price, 3) ?: '0' }}</td>
                <td>{{ number_format($item->discount_amount, 3) ?: '0' }}</td>
                <td>{{ number_format($item->tax, 3) ?: '0' }}</td>
                <td>{{ number_format($item->unit_sale_price, 3) ?: '0' }} {{ $currency }}</td>
                <td>{{ number_format($item->subtotal, 3) ?: '0' }} {{ $currency }}</td>
                <td>{{ $item->payment_methods ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    {{ __('attachment.general.empty') }}
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td class="bold" colspan="4">{{ __('attachment.general.subtotal') }}</td>
                <td class="bold" colspan="2">{{ __('attachment.product_purchase.subtotal') }}</td>
                <td>{{ number_format(collect($report)->sum('subtotal'), 3) ?: '0' }} {{ $currency }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
