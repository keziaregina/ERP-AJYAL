<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
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

        table {
            width: 100%;
            font-size: 10px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            text-align: center;
            padding: 2px;
            word-wrap: break-word;
        }

        .thead {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
        }

        .day-header {
            background-color: #ccc;
        }

        .name-cell {
            text-align: left;
        }

        .highlight-red {
            background-color: #e74c3c;
        }

        .highlight-yellow {
            background-color: #f1c40f;
        }

        .highlight-blue {
            background-color: #5dade2;
        }

        .highlight-green {
            background-color: #2ecc71;
        }

        .highlight-grey {
            background-color: #bdc3c7;
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

        $totalAllOvertime = $report['total_all_overtime'];

        $employees = $report['employees'];
        $totalAllOvertime = $totalAllOvertime;
        $logo = $logo;
        $business = $user->business_id;
        $location = $user->location_id;
        $month = now()->format('F');
        $year = now()->format('Y');
    @endphp
    <div class="header">
        <img class="logo" src="{{ $logo }}" alt="logo">
        <h1>Ajyal Al - Madina Al - Asria</h1>
        <span>{{ env('APP_TITLE') }}</span>

        {{ Log::info('CUSTOMER & SUPPLIER -------------------------------------------------->') }}
        {{ Log::info(json_encode($report, JSON_PRETTY_PRINT)) }}
    </div>

    <div class="report-title">
        {{ __('overtime.title') }}
        <br>
        <br>
        {{ strtoupper(__('overtime.month.' . strtolower($month))) }} {{ $year }}
    </div>

    <table>
        <thead>
            <tr class="thead">
                <th>{{ strtoupper(__('overtime.sl')) }}</th>
                {{-- <th>{{ strtoupper(__('overtime.badge')) }}</th> --}}
                <th>{{ strtoupper(__('overtime.name')) }}</th>
                @for ($d = 1; $d <= now()->daysInMonth; $d++)
                    <th>{{ $d }}</th>
                @endfor
                <th>{{ strtoupper(__('overtime.total_per_month')) }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $index => $employee)
                <tr @if ($loop->even) style="background-color: #f9f9f9;" @endif>
                    <td>{{ $index + 1 }}</td>
                    {{-- <td>{{ $employee['user_id'] }}</td> --}}
                    <td class="name-cell">{{ $employee['full_name'] }}</td>

                    @foreach ($employee['overtime_data'] as $day => $value)
                        @php
                            $bgClass = '';
                            if ($value == 'SL') {
                                $bgClass = 'highlight-yellow';
                            } elseif ($value == 'GE') {
                                $bgClass = 'highlight-green';
                            } elseif ($value == 'VL') {
                                $bgClass = 'highlight-blue';
                            } elseif ($value == 'A') {
                                $bgClass = 'highlight-red';
                            } 
                            // elseif ($value == 3) {
                            //     $bgClass = 'highlight-grey';
                            // } elseif ($value == 1) {
                            //     $bgClass = 'highlight-green';
                            // } elseif ($value == 11) {
                            //     $bgClass = 'highlight-red';
                            // }
                        @endphp

                        <td class="{{ $bgClass }}">{{ $value ?? '' }}</td>
                    @endforeach
                    <td>{{ $employee['total_overtime_by_month'] }}</td>
                </tr>
            @endforeach
            
            <!-- Total row -->
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td style="border: none;" colspan="2">{{ strtoupper(__('overtime.total_all')) }}</td>
                @for ($d = 1; $d <= now()->daysInMonth; $d++)
                    <td style="border: none;"></td>
                @endfor
                <td style="border: none; background-color: yellow">{{ $totalAllOvertime }}</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
