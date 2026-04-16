<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'IGRCFP') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f5f7fa;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            text-align: center;
        }
        .email-logo {
            max-width: 180px;
            height: auto;
            margin-bottom: 15px;
        }
        .email-body {
            padding: 40px;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px 40px;
            text-align: center;
            border-top: 1px solid #eaeaea;
            color: #666;
            font-size: 14px;
        }
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .help-text {
            background: #f0f7ff;
            border-left: 4px solid #667eea;
            padding: 12px;
            margin: 20px 0;
            font-size: 14px;
            color: #555;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            @if (isset($logo))
                <img src="{{ $logo }}" alt="IGRCFP Logo" class="email-logo">
            @endif
            <h1 style="color: #fff; margin: 0; font-size: 24px;">IGRCFP</h1>
        </div>

        <div class="email-body">
            @if (isset($slot))
                {{ $slot }}
            @else
                @php
                    $introLines = $introLines ?? [];
                    $outroLines = $outroLines ?? [];
                @endphp

                @if (! empty($greeting))
                    <h2 style="margin-top: 0; color: #111827;">{{ $greeting }}</h2>
                @else
                    @if (($level ?? null) === 'error')
                        <h2 style="margin-top: 0; color: #111827;">{{ __('Whoops!') }}</h2>
                    @else
                        <h2 style="margin-top: 0; color: #111827;">{{ __('Hello!') }}</h2>
                    @endif
                @endif

                @foreach ($introLines as $line)
                    <p>{{ $line }}</p>
                @endforeach

                @if (isset($actionText, $actionUrl))
                    <div style="margin: 20px 0;">
                        <a href="{{ $actionUrl }}" class="btn-primary">{{ $actionText }}</a>
                    </div>
                @endif

                @foreach ($outroLines as $line)
                    <p>{{ $line }}</p>
                @endforeach

                @if (! empty($salutation))
                    <p>{!! nl2br(e($salutation)) !!}</p>
                @else
                    <p>{{ __('Regards,') }}<br>{{ config('app.name') }}</p>
                @endif

                @if (isset($actionText, $actionUrl))
                    @php
                        $displayableActionUrl = str_replace(['mailto:', 'tel:'], '', $actionUrl);
                    @endphp
                    <div class="help-text">
                        {{ __("If you're having trouble clicking the \":actionText\" button, copy and paste the URL below into your web browser:", ['actionText' => $actionText]) }}
                        <br>
                        <a href="{{ $actionUrl }}">{{ $displayableActionUrl }}</a>
                    </div>
                @endif
            @endif
        </div>

        <div class="email-footer">
            <p style="margin: 0 0 10px 0;">
                &copy; {{ date('Y') }} IGRCFP. All rights reserved.
            </p>
            <p style="margin: 0; font-size: 12px; color: #999;">
                This is an automated message, please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
