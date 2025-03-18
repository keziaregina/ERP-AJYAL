<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profit / Loss Report    </title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        header img {
            width: 100px;
        }

        header h1 {
            font-size: 15px;
        }

        .card {
            background-color: #f4f7fa;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            min-width: 300px;
            margin-bottom: 20px;
        }

        .card h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: #444;
            font-size: 18px;
        }

        .card table {
            width: 100%;
            border-collapse: collapse;
        }

        .card table td {
            padding: 6px 0;
            color: #333;
        }

        .label {
            font-weight: bold;
        }

        .info {
            color: #3498db;
            cursor: pointer;
            margin-left: 5px;
        }

        .overall {
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .overall h4 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #444;
        }
        .overall p{
            font-size: 14px;
            color: #444;
        }
        .overall h3{
            font-size: 16px;
            margin:0 0 0 0;
            color: #444;
        }

        .negative {
            color: #e74c3c;
            /* font-weight: bold; */
        }
        
        @font-face {
    font-family: 'Amiri';
    src: url("{{ asset('fonts/Amiri-Regular.ttf') }}") format("truetype");
}

.arabic {
    direction: rtl;
    font-family: 'Amiri', sans-serif;
    font-weight: normal;
}

table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background-color: #243B55;
            color: white;
            text-align: left;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

    </style>
</head>


<body>
    <header>
        <img src="{{ $logo }}" alt="logo">
        <h1>Ajyal Al - Madina</h1>
        <span class="arabic">{{ env('APP_TITLE') }}</span>
    </header>
    <main>
        <div class="container">
            <h3 style="text-center">
                Report : {{ $dates['start_date'] }} ~ {{ $dates['end_date'] }}
            </h3>
        <h3>Activity Log</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="activity_log_table">
                <thead>
                    <tr>
                        <th>date</th>
                        <th>subject type</th>
                        <th>action</th>
                        <th>by</th>
                        <th>note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report as $activity)
                    <tr>
                        <td>{{ $activity->created_at }}</td>
                        <td>{{ $activity->subject_type }}</td>
                        <td>{{ $activity->description }}</td>
                        <td>{{ $activity->created_by }}</td>
                        <td class="arabic">{!! $activity->note !!}</td>
                    </tr>
                    @endforeach
                    
                </tbody>
                
              
            </table>
        </div>
    </main>
</body>

</html>
