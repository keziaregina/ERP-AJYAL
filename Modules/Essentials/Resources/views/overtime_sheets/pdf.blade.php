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
        {{ strtoupper(__('overtime.month.' . strtolower($month))) }} {{ $year }}
    </div>

    <table>
        <thead>
            <tr class="thead">
                <th>{{ strtoupper(__('overtime.sl')) }}</th>
                <th>{{ strtoupper(__('overtime.badge')) }}</th>
                <th>{{ strtoupper(__('overtime.name')) }}</th>
                @for ($d = 1; $d <= now()->daysInMonth; $d++)
                    <th>{{ $d }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $employee)
                <tr @if ($loop->even) style="background-color: #f9f9f9;" @endif>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $employee['user_id'] }}</td>
                    <td class="name-cell">{{ $employee['full_name'] }}</td>

                    @foreach ($employee['overtime_data'] as $day => $value)
                        @php
                            $bgClass = '';
                            if ($value == 8) {
                                $bgClass = 'highlight-yellow';
                            } elseif ($value == 'ML') {
                                $bgClass = 'highlight-blue';
                            } elseif ($value == 'A') {
                                $bgClass = 'highlight-red';
                            } elseif ($value == 3) {
                                $bgClass = 'highlight-grey';
                            } elseif ($value == 1) {
                                $bgClass = 'highlight-green';
                            } elseif ($value == 11) {
                                $bgClass = 'highlight-red';
                            }
                        @endphp

                        <td class="{{ $bgClass }}">{{ $value ?? '' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
