{{-- <!DOCTYPE html>
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
        .content-ltr {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
            text-align: left;
        }
        .content-rtl {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
            text-align: right;
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

        .rtl {
            direction: rtl
        }

        .ltr {
            direction: ltr
        }
    </style>
</head>
<body>
    @php
        if ($attachment_lang === 'ar') {
            \App::setLocale('ar');
        } else {
            \App::setLocale('en');
        }
    @endphp
    <div class="email-container {{ $attachment_lang === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="header">{{ __('email_notification.title', ['interval' => __("interval.$interval")]) }}</div>
        <div class="content-{{ $attachment_lang === 'ar' ? 'rtl' : 'ltr' }}">
            <p>{{ __('email_notification.row_1', ['first_name' => $user->first_name, 'last_name' => $user->last_name]) }}</p>
            <p>{{ __('email_notification.row_2', ['report_type' => __("report_type.$title")]) }}</p>
            <a href="#" class="button">{{ __('email_notification.download_button') }}</a>
        </div>
        <div class="footer">&copy; {{ date('Y') }} {{ __('email_notification.copyright') }}</div>
    </div>
</body>
</html> --}}
{{-- <!DOCTYPE html>
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
            text-align: center;
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
        }
        .button {
            display: inline-block;
            background: #28a745;
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
        <div class="header">Your Report is Ready</div>
        <div class="content">
            <p>Hi {{ $user->first_name }} {{ $user->last_name }},</p>
            <p>Your latest report is now available. Click the button below to download it.</p>
            <a href="#" class="button">Download Report</a>
        </div>
        <div class="footer">&copy; {{ date('Y') }} Your Company. All rights reserved.</div>
    </div>
</body>
</html> --}}
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
        <div class="header">Your Report is Ready</div>
        <div class="content">
            <p>Hi {{ $user->first_name }} {{ $user->last_name }},</p>
            <p>Your latest report is now available. Click the button below to download it.</p>
            <a href="#" class="button">Download Report</a>
        </div>
        <div class="footer">&copy; {{ date('Y') }} Your Company. All rights reserved.</div>
    </div>
</body>
</html>
