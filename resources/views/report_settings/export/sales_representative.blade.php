<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Representative Report</title>
    
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

        .header-box{
            margin-left: 20px;
        }

        .box {
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8fafc;
        }

        .box table {
            border: none;
            width: 100%;
            border-collapse: collapse;
        }

        .box th, .box td {
            border: none;
            text-align: left;
            padding: 10px;
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

        .box {
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f8fafc;
            padding: 10px;
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
        <p class="label">{{ __('attachment.sales_representative.summary') }}</p>
        <div class="box">
            <table class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
                <tr>
                    <td>
                        <div class="label">{{ __('attachment.sales_representative.total_sale-return') }}</div>
                        <div class="value">
                            {{ number_format($report['overall']['sell']['total_sell_exc_tax'], 3) ?: '0' }} {{ $currency }} - 
                            {{ number_format($report['overall']['sell']['total_sell_return_exc_tax'], 3) ?: '0' }} {{ $currency }} = 
                            {{ number_format($report['overall']['sell']['total_sell'], 3) ?: '0' }} {{ $currency }}</div>
                    </td>
                    <td>
                        <div class="label">{{ __('attachment.sales_representative.total_expense') }}
                        </div>
                        <div class="value">
                            {{ number_format($report['overall']['expense']['total_expense'], 3) ?: '0' }} {{ $currency }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <h3 class="label">{{ __('attachment.sales_representative.sales') }}</h3>
    <table>
        <thead>
            <tr>
                <th class="indexing">#</th>
                <th>{{ __('attachment.sales_representative.th_date') }}</th>
                <th>{{ __('attachment.sales_representative.th_invoice_no') }}</th>
                <th>{{ __('attachment.sales_representative.th_cust_name') }}</th>
                <th>{{ __('attachment.sales_representative.th_location') }}</th>
                <th>{{ __('attachment.sales_representative.th_payment_status') }}</th>
                <th>{{ __('attachment.sales_representative.th_amount') }}</th>
                <th>{{ __('attachment.sales_representative.th_paid') }}</th>
                <th>{{ __('attachment.sales_representative.th_remaining') }}</th>
            </tr>
        </thead>
        <tbody>
                @forelse ($report['collection']['sales'] as $index => $item)
                <tr>
                    <td class="indexing">{{ $index + 1 }}</td>
                    <td>{{ $item->transaction_date ?: '-' }}</td>
                    <td>{{ $item->invoice_no ?: '-' }}</td>
                    <td>{{ $item->conatct_name ?: '-' }}</td>
                    <td>{{ $item->business_location ?: '-' }}</td>
                    <td>{{ $item->payment_status ?: '-' }}</td>
                    <td>{{ number_format($item->final_total, 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ number_format($item->total_paid, 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ number_format($item->total_remaining, 3) ?: '0' }} {{ $currency }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">{{ __('attachment.general.empty') }}</td>
                </tr>
                @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td class="bold" colspan="5">{{ __('attachment.general.subtotal') }}</td>
                <td>{{ number_format(collect($report['collection']['sales'])->sum('final_total'), 3) }} {{ $currency }}</td>
                <td>{{ number_format(collect($report['collection']['sales'])->sum('total_paid'), 3) }} {{ $currency }}</td>
                <td>
                    <div><span class="bold">{{ __('attachment.sales_representative.sell_due') }}</span> ~ {{ number_format(collect($report['collection']['sales'])->sum('payment_due'), 3) }} {{ $currency }}</div>
                    <div><span class="bold">{{ __('attachment.sales_representative.sell_return_due') }}</span> ~ {{ number_format(collect($report['collection']['sales'])->sum('sell_return_due'), 3) }} {{ $currency }}</div>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <h3 class="label">{{ __('attachment.sales_representative.expenses') }}</h3>
    <table>
        <thead>
            <tr>
                <th class="indexing">#</th>
                <th>{{ __('attachment.sales_representative.th_date') }}</th>
                <th>{{ __('attachment.sales_representative.th_ref_no') }}</th>
                <th>{{ __('attachment.sales_representative.th_expense_cat') }}</th>
                <th>{{ __('attachment.sales_representative.th_location') }}</th>
                <th>{{ __('attachment.sales_representative.th_payment_status') }}</th>
                <th>{{ __('attachment.sales_representative.th_amount') }}</th>
                <th>{{ __('attachment.sales_representative.th_expense_for') }}</th>
                <th>{{ __('attachment.sales_representative.th_expense_note') }}</th>
            </tr>
        </thead>
        <tbody>
                @forelse ($report['collection']['expense'] as $index => $item)
                <tr>
                    <td class="indexing">{{ $index + 1 }}</td>
                    <td>{{ $item->transaction_date ?: '-' }}</td>
                    <td>{{ $item->ref_no ?: '-' }}</td>
                    <td>{{ $item->category ?: '-' }}</td>
                    <td>{{ $item->location_name ?: '-' }}</td>
                    <td>{{ $item->payment_status ?: '-' }}</td>
                    <td>{{ number_format($item->final_total, 3) ?: '0' }} {{ $currency }}</td>
                    <td>{{ $item->expense_for ?: '-' }}</td>
                    <td>{{ $item->additional_notes ?: '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">{{ __('attachment.general.empty') }}</td>
                </tr>
                @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td class="bold" colspan="6">{{ __('attachment.general.subtotal') }}</td>
                <td>{{ number_format(collect($report['collection']['expense'])->sum('final_total'), 3) }} {{ $currency }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    </main>
</body>
</html>
