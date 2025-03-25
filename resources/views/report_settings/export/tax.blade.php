<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tax Report</title>

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

        .box {
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f8fafc;
            padding: 20px;
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

    <div class="box">
        <div class="label {{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            {{ __('attachment.tax.overall_title') }}
        </div>
        <div class="value {{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            {{ __('attachment.tax.overall_value') }}
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 class="title {{ $lang === 'ar' ? 'rtl' : 'ltr' }}" style="margin-bottom: 15px">
            {{ __('attachment.tax.input_title') }}
        </h3>

        <table class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            <thead>
                <tr>
                    <th class="indexing">#</th>
                    <th>{{ __('attachment.tax.th_date') }}</th>
                    <th>{{ __('attachment.tax.th_ref') }}</th>
                    <th>{{ __('attachment.tax.th_supplier') }}</th>
                    <th>{{ __('attachment.tax.th_taxnum') }}</th>
                    <th>{{ __('attachment.tax.th_amount') }}</th>
                    <th>{{ __('attachment.tax.th_payment_method') }}</th>
                    <th>{{ __('attachment.tax.th_discount') }}</th>
                    @foreach ($report['taxes'] as $tax)
                        <th>{{ $tax['name'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if (count($report['input_tax_details']['tax_details']) > 0)
                    @foreach ($report['input_tax_details']['tax_details'] as $index => $item)
                        <tr>
                            <td class="indexing">{{ $index + 1 }}</td>
                            <td>{{ $item->transaction_date ?: '-' }}</td>
                            <td>{{ $item->ref_no ?: '-' }}</td>
                            <td>{{ $item->contact_name ?: '-' }}</td>
                            <td>{{ $item->tax_number ?: '-' }}</td>
                            <td>{{ number_format($item->total_before_tax, 3) ?: '0' }} {{ $currency }}</td>
                            <td>{{ $item->payment_methods ?: '-' }}</td>
                            <td>{{ number_format($item->discount_amount, 3) ?: '0' }} {{ $currency }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11">{{ __('attachment.general.empty') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 class="title {{ $lang === 'ar' ? 'rtl' : 'ltr' }}" style="margin-bottom: 15px">
            {{ __('attachment.tax.output_title') }}
        </h3>

        <table class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            <thead>
                <tr>
                    <th class="indexing">#</th>
                    <th>{{ __('attachment.tax.th_date') }}</th>
                    <th>{{ __('attachment.tax.th_ref') }}</th>
                    <th>{{ __('attachment.tax.th_customer') }}</th>
                    <th>{{ __('attachment.tax.th_taxnum') }}</th>
                    <th>{{ __('attachment.tax.th_amount') }}</th>
                    <th>{{ __('attachment.tax.th_payment_method') }}</th>
                    <th>{{ __('attachment.tax.th_discount') }}</th>
                    @foreach ($report['taxes'] as $tax)
                        <th>{{ $tax['name'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if (count($report['output_tax_details']['tax_details']) > 0)
                    @foreach ($report['output_tax_details']['tax_details'] as $index => $item)
                        <tr class="indexing">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->transaction_date ?: '-' }}</td>
                            <td>{{ $item->invoice_no ?: '-' }}</td>
                            <td>{{ $item->contact_name ?: '-' }}</td>
                            <td>{{ $item->tax_number ?: '-' }}</td>
                            <td>{{ number_format($item->total_before_tax, 3) ?: '0' }} {{ $currency }}</td>
                            <td>{{ $item->payment_methods ?: '-' }}</td>
                            <td>{{ number_format($item->discount_amount, 3) ?: '0' }} {{ $currency }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11">{{ __('attachment.general.empty') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 class="title {{ $lang === 'ar' ? 'rtl' : 'ltr' }}" style="margin-bottom: 15px">
            {{ __('attachment.tax.expense_title') }}
        </h3>

        <table class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            <thead>
                <tr>
                    <th class="indexing">#</th>
                    <th>{{ __('attachment.tax.th_date') }}</th>
                    <th>{{ __('attachment.tax.th_ref') }}</th>
                    <th>{{ __('attachment.tax.th_taxnum') }}</th>
                    <th>{{ __('attachment.tax.th_amount') }}</th>
                    <th>{{ __('attachment.tax.th_payment_method') }}</th>
                    <th>{{ __('attachment.tax.th_discount') }}</th>
                    @foreach ($report['taxes'] as $tax)
                        <th>{{ $tax['name'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if (count($report['expense_tax_details']['tax_details']) > 0)
                    @foreach ($report['expense_tax_details']['tax_details'] as $index => $item)
                        <tr>
                            <td class="indexing">{{ $index + 1 }}</td>
                            <td>{{ $item->transaction_date ?: '-' }}</td>
                            <td>{{ $item->ref_no ?: '-' }}</td>
                            <td>{{ $item->tax_number ?: '-' }}</td>
                            <td>{{ number_format($item->total_before_tax, 3) ?: '0' }} {{ $currency }}</td>
                            <td>{{ $item->payment_methods ?: '-' }}</td>
                            <td>{{ number_format($item->discount_amount, 3) ?: '0' }} {{ $currency }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="10">{{ __('attachment.general.empty') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>
