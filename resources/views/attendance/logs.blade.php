@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-primary mb-1">出席リスト</h1>
            <h2 class="h5 text-muted mb-1">{{ $attendance->title }}</h2>
            <p class="text-muted small mb-0">
                <i class="far fa-calendar-alt me-1"></i>
                {{ $attendance->start_time->format('Y年m月d日 H:i') }} - {{ $attendance->end_time->format('Y年m月d日 H:i') }}
            </p>
        </div>
        <div class="mt-3 mt-md-0 d-flex flex-wrap gap-2">
            <a href="{{ route('attendance.show', $attendance->id) }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> 戻る
            </a>
            <a href="{{ route('attendance.export', $attendance->id) }}" class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i> エクセルでエクスポート
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                <div class="text-muted small">
                    <i class="fas fa-users me-1"></i> 参加者合計: <strong>{{ $logs->total() }}</strong>
                    @if(request('search'))
                        | 検索: <strong>"{{ request('search') }}"</strong>
                    @endif
                </div>
                <form method="GET" class="mt-2 mt-md-0">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="名前またはIDで検索..."
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                        @if(request('search'))
                        <a href="{{ route('attendance.logs', $attendance->id) }}" class="btn btn-outline-danger">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped align-middle text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>ID/NIM/NIP</th>
                            <th>氏名</th>
                            <th class="text-center">出席時間</th>
                            <th class="text-center">ステータス</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $index }}</td>
                            <td><strong>{{ $log->user_id }}</strong></td>
                            <td>{{ $log->name }}</td>
                            <td class="text-center">
                                <div>{{ $log->scan_time->format('Y/m/d') }}</div>
                                <small class="text-muted">{{ $log->scan_time->format('H:i:s') }}</small>
                            </td>
                            <td class="text-center">
                                @if($log->status === 'present')
                                <span class="badge bg-success">出席</span>
                                @else
                                <span class="badge bg-warning text-dark">遅刻</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-user-slash fa-2x text-muted mb-2"></i><br>
                                <span class="text-muted">出席データがありません</span>
                                @if(request('search'))
                                <div>
                                    <a href="{{ route('attendance.logs', $attendance->id) }}" class="btn btn-sm btn-link mt-2">
                                        すべて表示
                                    </a>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $logs->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
