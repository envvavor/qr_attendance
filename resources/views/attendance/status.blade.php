<!DOCTYPE html>
<html>
<head>
    <title>出席状況 - {{ $attendance->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-card {
            max-width: 500px;
            margin: 2rem auto;
            text-align: center;
        }
        .status-not-open {
            border-left: 5px solid #ffc107;
        }
        .status-open {
            border-left: 5px solid #28a745;
        }
        .status-closed {
            border-left: 5px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card status-card status-{{ $status['type'] }}">
            <div class="card-header">
                <h2>{{ $attendance->title }}</h2>
            </div>
            <div class="card-body">
                <h3 class="card-title">{{ $status['message'] }}</h3>
                <p class="card-text">{{ $status['details'] }}</p>
                <p class="text-muted">現在時刻: {{ $current_time }}</p>
                
                @if($status['type'] === 'open')
                    <a href="{{ route('attendance.scan-form', $attendance->id) }}" 
                       class="btn btn-primary">
                        出席フォームへ進む
                    </a>
                @endif

                @if($status['type'] === 'not_open')
                    <div id="countdown" class="mt-3"></div>
                    <script>
                        const startTime = new Date("{{ $attendance->start_time->toIso8601String() }}").getTime();
                        
                        const countdown = setInterval(function() {
                            const now = new Date().getTime();
                            const distance = startTime - now;
                            
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            
                            document.getElementById("countdown").innerHTML = `
                                開始まで: 
                                ${hours}時間 ${minutes}分 ${seconds}秒
                            `;
                            
                            if (distance < 0) {
                                clearInterval(countdown);
                                window.location.reload();
                            }
                        }, 1000);
                    </script>
                @endif
                
            </div>
        </div>
    </div>
</body>
</html>