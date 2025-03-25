<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Purchase Summary</title>
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
                <th class="indexing" rowspan="2">#</th>
                <th>{{ __('attachment.product_purchase.th_product') }}</th>
                <th>{{ __('attachment.product_purchase.th_sku') }}</th>
                <th>{{ __('attachment.product_purchase.th_supplier') }}</th>
                <th>{{ __('attachment.product_purchase.th_ref_no') }}</th>
                <th>{{ __('attachment.product_purchase.th_date') }}</th>
            </tr>
            <tr>
                <th>{{ __('attachment.product_purchase.th_quantity') }}</th>
                <th>{{ __('attachment.product_purchase.th_tunit_adjusted') }}</th>
                <th>{{ __('attachment.product_purchase.th_unit_purchase_price') }}</th>
                <th colspan="2">{{ __('attachment.product_purchase.subtotal') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $index => $item)
            <tr>
                <td class="indexing" rowspan="2">{{ $index + 1 }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->sub_sku }}</td>
                <td>{{ $item->supplier }}</td>
                <td>{{ $item->ref_no }}</td>
                <td>{{ $item->transaction_date }}</td>
            </tr>
            <tr>
                <td>{{ number_format($item->purchase_qty, 3) }}</td>
                <td>{{ number_format($item->quantity_adjusted, 3) }}</td>
                <td>{{ number_format($item->unit_purchase_price, 3) ?: '0' }} {{ $currency }}</td>
                <td colspan="2">{{ number_format($item->subtotal, 3) ?: '0' }} {{ $currency }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    {{ __('attachment.general.empty') }}
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td class="bold" colspan="6">{{ __('attachment.general.subtotal') }}</td>
            </tr>
            <tr class="total">
                <td class="bold" colspan="3">{{ __('attachment.product_purchase.tf_unit_purchase_price') }}</td>
                <td class="bold" colspan="3">{{ __('attachment.product_purchase.subtotal') }}</td>
            </tr>
            <tr>
                <td colspan="3">{{ number_format(collect($report)->sum('quantity_adjusted'), 3) ?: '0' }} {{ $currency }}</td>
                <td colspan="3">{{ number_format(collect($report)->sum('subtotal'), 3) ?: '0' }} {{ $currency }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
