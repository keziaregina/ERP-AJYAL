<!DOCTYPE html>
<html>
<head>
    <title>Customer Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }
        .content {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
            text-align: left;
        }
        .button {
            display: inline-block;
            background: #007bff;
            color: #ffffff;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">Your {{ $interval }} report is ready</div>
        <div class="content">
            {{-- @dd($report_type) --}}
            <p>Hi {{ $user->first_name }} {{ $user->last_name }},</p>
            <p>Your latest {{ $report_type }} report is now available. Click the button below to download it.</p>
            <a href="#" class="button">Download Report</a>
        </div>
        <div class="footer">&copy; {{ date('Y') }} Your Company. All rights reserved.</div>
    </div>
</body>
</html>
