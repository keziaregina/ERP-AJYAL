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
    <div class="header">
        <img class="logo" src="{{ $logo }}" alt="logo">
        <h1>Ajyal Al - Madina Al - Asria</h1>
        <span>{{ env('APP_TITLE') }}</span>
    </div>

    <div class="report-title">
        {{ __('overtime.title') }}
        <br>
        <br>
        {{ strtoupper(__('overtime.month.' . strtolower($month_name))) }} {{ $year }}
    </div>

    <table>
        <thead>
            <tr class="thead">
                <th>{{ strtoupper(__('overtime.sl')) }}</th>
                {{-- <th>{{ strtoupper(__('overtime.badge')) }}</th> --}}
                <th>{{ strtoupper(__('overtime.name')) }}</th>
                @for ($d = 1; $d <= now()->month($month)->daysInMonth; $d++)
                    <th>{{ $d }}</th>
                @endfor
                <th>{{ strtoupper(__('overtime.total_per_month')) }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $employee)
                <tr @if ($loop->even) style="background-color: #f9f9f9;" @endif>
                    <td>{{ $loop->iteration }}</td>
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
                        @endphp

                        <td class="{{ $bgClass }}">{{ $value ?? '' }}</td>
                    @endforeach
                    <td>{{ $employee['total_overtime_by_month'] }}</td>
                </tr>
            @endforeach
            
            <!-- Total row -->
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td style="border: none;" colspan="2">{{ strtoupper(__('overtime.total_all')) }}</td>
                @for ($d = 1; $d <= now()->month($month)->daysInMonth; $d++)
                    <td style="border: none;"></td>
                @endfor
                <td style="border: none; background-color: yellow">{{ $totalAllOvertime }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Ge Employee Section -->
    <div style="margin-top: 20px; border: 1px solid #FFB22C; padding: 10px; width: 100%; background-color: #FEF3E2;">
        <div style="align-items: center; font-size: 12px; justify-content: start; color: #FA812F;">
            <h4>{{ __('essentials::lang.employee_of_the_month') }}</h4>          
            <h3>{{ $GloriousName }}</h3>                
        </div>
    </div>

    <!-- Legend Section -->
    <div style="margin-top: 20px; border: 1px solid #ddd; padding: 10px; background-color: #f9f9f9;">
        <h3 style="font-size: 12px; margin-bottom: 10px; text-align: center;">{{ __('essentials::lang.legend') }}</h3>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="border: none; width: 25%;">
                    <div style="display: flex; align-items: center;">
                        <div style="width: 20px; height: 20px; background-color: #e74c3c; border-radius: 50%; margin-right: 5px;"></div>
                        <span style="font-size: 10px;">A - {{ __('essentials::lang.absent') }}</span>
                    </div>
                </td>
                <td style="border: none; width: 25%;">
                    <div style="display: flex; align-items: center;">
                        <div style="width: 20px; height: 20px; background-color: #5dade2; border-radius: 50%; margin-right: 5px;"></div>
                        <span style="font-size: 10px;">VL - {{ __('essentials::lang.vacation_leave') }}</span>
                    </div>
                </td>
                <td style="border: none; width: 25%;">
                    <div style="display: flex; align-items: center;">
                        <div style="width: 20px; height: 20px; background-color: #2ecc71; border-radius: 50%; margin-right: 5px;"></div>
                        <span style="font-size: 10px;">GE - {{ __('essentials::lang.glorious_employee_allowance') }}</span>
                    </div>
                </td>
                <td style="border: none; width: 25%;">
                    <div style="display: flex; align-items: center;">
                        <div style="width: 20px; height: 20px; background-color: #f1c40f; border-radius: 50%; margin-right: 5px;"></div>
                        <span style="font-size: 10px;">SL - {{ __('essentials::lang.sick_leave') }}</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>
