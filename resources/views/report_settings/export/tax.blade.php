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

        $input = json_decode(Cache::get('colvisState_input_tax'), true) ?? [];
        $output = json_decode(Cache::get('colvisState_output_tax'), true) ?? [];
        $expense = json_decode(Cache::get('colvisState_expense_tax'), true) ?? [];

        $colCountInput = 1;
        $colCountOutput = 1;
        $colCountExpense = 1;

        foreach (range(0, 6) as $i) {
            if (!isset($colvis[$i]) || $colvis[$i] !== false) {
                $colCountInput++;
            }
        }
        foreach (range(0, 6) as $i) {
            if (!isset($colvis[$i]) || $colvis[$i] !== false) {
                $colCountOutput++;
            }
        }
        foreach (range(0, 5) as $i) {
            if (!isset($colvis[$i]) || $colvis[$i] !== false) {
                $colCountExpense++;
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

        <table style="margin-top: 10px" class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            <thead>
                <tr>
                    <th class="indexing">#</th>
                    @if (!isset($input[0]) || $input[0] !== false)
                        <th>{{ __('attachment.tax.th_date') }}</th>
                    @endif
                    @if (!isset($input[1]) || $input[1] !== false)
                        <th>{{ __('attachment.tax.th_ref') }}</th>
                    @endif
                    @if (!isset($input[2]) || $input[2] !== false)
                        <th>{{ __('attachment.tax.th_supplier') }}</th>
                    @endif
                    @if (!isset($input[3]) || $input[3] !== false)
                        <th>{{ __('attachment.tax.th_taxnum') }}</th>
                    @endif
                    @if (!isset($input[4]) || $input[4] !== false)
                        <th>{{ __('attachment.tax.th_amount') }}</th>
                    @endif
                    @if (!isset($input[5]) || $input[5] !== false)
                        <th>{{ __('attachment.tax.th_payment_method') }}</th>
                    @endif
                    @if (!isset($input[6]) || $input[6] !== false)
                        <th>{{ __('attachment.tax.th_discount') }}</th>
                    @endif
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
                            @if (!isset($input[0]) || $input[0] !== false)
                                <td>{{ $item->transaction_date ?: '-' }}</td>
                            @endif
                            @if (!isset($input[1]) || $input[1] !== false)
                                <td>{{ $item->ref_no ?: '-' }}</td>
                            @endif
                            @if (!isset($input[2]) || $input[2] !== false)
                                <td>{{ $item->contact_name ?: '-' }}</td>
                            @endif
                            @if (!isset($input[3]) || $input[3] !== false)
                                <td>{{ $item->tax_number ?: '-' }}</td>
                            @endif
                            @if (!isset($input[4]) || $input[4] !== false)
                                <td>{{ number_format($item->total_before_tax, 3) ?: '0' }} {{ $currency }}</td>
                            @endif
                            @if (!isset($input[5]) || $input[5] !== false)
                                <td>{{ $item->payment_methods ?: '-' }}</td>
                            @endif
                            @if (!isset($input[6]) || $input[6] !== false)
                                <td>{{ number_format($item->discount_amount, 3) ?: '0' }} {{ $currency }}</td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan={{ $colCountInput }}>{{ __('attachment.general.empty') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 class="title {{ $lang === 'ar' ? 'rtl' : 'ltr' }}" style="margin-bottom: 15px">
            {{ __('attachment.tax.output_title') }}
        </h3>

        <table style="margin-top: 10px" class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            <thead>
                <tr>
                    <th class="indexing">#</th>
                    @if (!isset($output[0]) || $output[0] !== false)
                        <th>{{ __('attachment.tax.th_date') }}</th>
                    @endif
                    @if (!isset($output[1]) || $output[1] !== false)
                        <th>{{ __('attachment.tax.th_ref') }}</th>
                    @endif
                    @if (!isset($output[2]) || $output[2] !== false)
                        <th>{{ __('attachment.tax.th_customer') }}</th>
                    @endif
                    @if (!isset($output[3]) || $output[3] !== false)
                        <th>{{ __('attachment.tax.th_taxnum') }}</th>
                    @endif
                    @if (!isset($output[4]) || $output[4] !== false)
                        <th>{{ __('attachment.tax.th_amount') }}</th>
                    @endif
                    @if (!isset($output[5]) || $output[5] !== false)
                        <th>{{ __('attachment.tax.th_payment_method') }}</th>
                    @endif
                    @if (!isset($output[6]) || $output[6] !== false)
                        <th>{{ __('attachment.tax.th_discount') }}</th>
                    @endif
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
                            @if (!isset($output[0]) || $output[0] !== false)
                                <td>{{ $item->transaction_date ?: '-' }}</td>
                            @endif
                            @if (!isset($output[1]) || $output[1] !== false)
                                <td>{{ $item->invoice_no ?: '-' }}</td>
                            @endif
                            @if (!isset($output[2]) || $output[2] !== false)
                                <td>{{ $item->contact_name ?: '-' }}</td>
                            @endif
                            @if (!isset($output[3]) || $output[3] !== false)
                                <td>{{ $item->tax_number ?: '-' }}</td>
                            @endif
                            @if (!isset($output[4]) || $output[4] !== false)
                                <td>{{ number_format($item->total_before_tax, 3) ?: '0' }} {{ $currency }}</td>
                            @endif
                            @if (!isset($output[5]) || $output[5] !== false)
                                <td>{{ $item->payment_methods ?: '-' }}</td>
                            @endif
                            @if (!isset($output[6]) || $output[6] !== false)
                                <td>{{ number_format($item->discount_amount, 3) ?: '0' }} {{ $currency }}</td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan={{ $colCountOutput }}>{{ __('attachment.general.empty') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 class="title {{ $lang === 'ar' ? 'rtl' : 'ltr' }}" style="margin-bottom: 15px">
            {{ __('attachment.tax.expense_title') }}
        </h3>

        <table style="margin-top: 10px" class="{{ $lang === 'ar' ? 'rtl' : 'ltr' }}">
            <thead>
                <tr>
                    <th class="indexing">#</th>
                    @if (!isset($expense[0]) || $expense[0] !== false)
                        <th>{{ __('attachment.tax.th_date') }}</th>
                    @endif
                    @if (!isset($expense[1]) || $expense[1] !== false)
                        <th>{{ __('attachment.tax.th_ref') }}</th>
                    @endif
                    @if (!isset($expense[2]) || $expense[2] !== false)
                        <th>{{ __('attachment.tax.th_taxnum') }}</th>
                    @endif
                    @if (!isset($expense[3]) || $expense[3] !== false)
                        <th>{{ __('attachment.tax.th_amount') }}</th>
                    @endif
                    @if (!isset($expense[4]) || $expense[4] !== false)
                        <th>{{ __('attachment.tax.th_payment_method') }}</th>
                    @endif
                    @if (!isset($expense[5]) || $expense[5] !== false)
                        <th>{{ __('attachment.tax.th_discount') }}</th>
                    @endif
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
                            @if (!isset($expense[0]) || $expense[0] !== false)
                                <td>{{ $item->transaction_date ?: '-' }}</td>
                            @endif
                            @if (!isset($expense[1]) || $expense[1] !== false)
                                <td>{{ $item->ref_no ?: '-' }}</td>
                            @endif
                            @if (!isset($expense[2]) || $expense[2] !== false)
                                <td>{{ $item->tax_number ?: '-' }}</td>
                            @endif
                            @if (!isset($expense[3]) || $expense[3] !== false)
                                <td>{{ number_format($item->total_before_tax, 3) ?: '0' }} {{ $currency }}</td>
                            @endif
                            @if (!isset($expense[4]) || $expense[4] !== false)
                                <td>{{ $item->payment_methods ?: '-' }}</td>
                            @endif
                            @if (!isset($expense[5]) || $expense[5] !== false)
                                <td>{{ number_format($item->discount_amount, 3) ?: '0' }} {{ $currency }}</td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan={{ $colCountExpense }}>{{ __('attachment.general.empty') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>
