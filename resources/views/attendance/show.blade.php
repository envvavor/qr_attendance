@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1>{{ $attendance->title }}</h1>
    <p>{{ $attendance->description }}</p>
    <p>有効期間: {{ $attendance->start_time->format('Y年m月d日 H:i') }} 〜 {{ $attendance->end_time->format('Y年m月d日 H:i') }}</p>
    
    <div class="my-4">
        {!! $attendance->qr_code !!}
    </div>
    
    <p>このQRコードをスキャンして出席してください</p>
    <a href="{{ route('attendance.scan', $attendance->id) }}" class="btn btn-primary" target="_blank">出席リンク</a>

    <div class="mt-4">
    <a href="{{ route('attendance.logs', $attendance->id) }}" class="btn btn-info">
        参加者リストを見る
    </a>
</div>
</div>
@endsection
