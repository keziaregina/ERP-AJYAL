<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Group Report</title>
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

        $colvis = json_decode(Cache::get('colvisState_activity_log'), true) ?? [];
        $colCount = 1;

        foreach (range(0, 1) as $i) {
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
                    <th>{{ __('attachment.cg.th_cg') }}</th>
                @endif
                @if (!isset($colvis[1]) || $colvis[1] !== false)
                    <th>{{ __('attachment.cg.th_total') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $index => $item)
                <tr>
                    <td class="indexing">{{ $index + 1 }}</td>
                    @if (!isset($colvis[0]) || $colvis[0] !== false)
                        <td>{{ $item['name'] ?: '-' }}</td>
                    @endif
                    @if (!isset($colvis[1]) || $colvis[1] !== false)
                        <td>{{ number_format($item['total_sell'], 3) ?: '0' }} {{ $currency }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan={{ $colCount }}>{{ __('attachment.general.empty') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
