<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Items Summary</title>
    
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
                <th>{{ __('attachment.items.th_product') }}</th>
                <th>{{ __('attachment.items.th_sku') }}</th>
                <th>{{ __('attachment.items.th_purchase_date') }}</th>
                <th>{{ __('attachment.items.th_purchase') }}</th>
                <th>{{ __('attachment.items.th_supplier') }}</th>
                <th colspan="2">{{ __('attachment.items.th_purchase_price') }}</th>
            </tr>
            <tr>
                <th>{{ __('attachment.items.th_sell_date') }}</th>
                <th>{{ __('attachment.items.th_sale') }}</th>
                <th>{{ __('attachment.items.th_customer') }}</th>
                <th>{{ __('attachment.items.th_location') }}</th>
                <th>{{ __('attachment.items.th_sell_quantity') }}</th>
                <th>{{ __('attachment.items.th_selling_price') }}</th>
                <th>{{ __('attachment.items.th_subtotal') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $index => $item)
                @php
                    $sell_date = \Carbon\Carbon::parse($item->sell_date);
                @endphp
                <tr>
                    <td class="indexing" rowspan="2">{{ $index + 1 }}</td>
                    <td>{{ $item->product_name ?: '-' }}</td>
                    <td>{{ $item->sku ?: '-' }}</td>
                    <td>{{ $item->purchase_date ?: '-' }}</td>
                    <td>{{ $item->purchase_ref_no ?: '-' }}</td>
                    <td>{{ $item->supplier ?: '-' }}</td>
                    <td colspan="2">{{ number_format($item->purchase_price, 3) ?: '0' }} {{ $currency }}</td>
                </tr>
                <tr>
                    <td>
                        {{ $sell_date->format('Y-m-d') }}
                        <br>
                        {{ $sell_date->format('H:i:s') }}
                    </td>
                    <td>{{ $item->sale_invoice_no ?: '-' }}</td>
                    <td>{{ $item->customer ?: '-' }}</td>
                    <td>{{ $item->location ?: '-' }}</td>
                    <td>{{ $item->quantity ?: '-' }}</td>
                    <td>{{ number_format($item->selling_price, 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ number_format($item->subtotal, 3) ?: '0' }} {{ $currency }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14">
                        {{ __('attachment.general.empty') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td class="bold" colspan="8">{{ __('attachment.general.subtotal') }}</td>
            </tr>
            <tr class="total">
                <td class="bold" colspan="2">{{ __('attachment.items.tf_purchase_price') }}</td>
                <td class="bold" colspan="2">{{ __('attachment.items.tf_quantity') }}</td>
                <td class="bold" colspan="2">{{ __('attachment.items.tf_selling_price') }}</td>
                <td class="bold" colspan="2">{{ __('attachment.items.tf_subtotal') }}</td>
            </tr>
            <tr>
                <td colspan="2">{{ number_format(collect($report)->sum('purchase_price'), 3) ?: '0' }} {{ $currency }}</td>
                <td colspan="2">{{ number_format(collect($report)->sum('quantity'), 3) ?: '0' }}</td>
                <td colspan="2">{{ number_format(collect($report)->sum('row_selling_price'), 3) ?: '0' }} {{ $currency }}</td>
                <td colspan="2">{{ number_format(collect($report)->sum('subtotal'), 3) ?: '0' }} {{ $currency }}</td>
            </tr>

        </tfoot>
    </table>
</body>

</html>
