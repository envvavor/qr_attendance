@extends('layouts.app')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center py-5 text-center">

    <div class="card shadow-sm border-0 w-100" style="max-width: 600px;">
        <div class="card-body p-4">

            <h1 class="h4 fw-bold text-primary mb-2">{{ $attendance->title }}</h1>
            <p class="text-muted mb-3">{{ $attendance->description }}</p>

            <p class="mb-1 text-secondary">
                有効期間: 
                <strong>{{ $attendance->start_time->format('Y年m月d日 H:i') }}</strong> 〜 
                <strong>{{ $attendance->end_time->format('Y年m月d日 H:i') }}</strong>
            </p>

            <div class="my-4">
                <div class="border rounded p-3 d-inline-block bg-white">
                    {!! $attendance->qr_code !!}
                </div>
            </div>

            <p class="mb-3 text-muted">このQRコードをスキャンして出席してください</p>

            <a href="{{ route('attendance.scan', $attendance->id) }}" class="btn btn-primary mb-2" target="_blank">
                <i class="fas fa-qrcode me-2"></i> 出席リンクを開く
            </a>

            <div class="mt-3">
                <a href="{{ route('attendance.logs', $attendance->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-users me-2"></i> 参加者リストを見る
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
