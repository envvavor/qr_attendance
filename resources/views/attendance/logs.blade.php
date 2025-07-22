@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header section remains the same -->
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
            <form method="GET" action="{{ route('attendance.export', $attendance->id) }}" class="d-inline form-export-excel">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-file-excel me-1"></i> エクセルでエクスポート
                </button>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Tombol Hapus Semua -->
            @if($logs->count() > 0)
            <div class="mb-3 d-flex justify-content-end">
                <form method="POST" action="{{ route('attendance.deleteAllLogs', $attendance->id) }}" class="form-delete-all">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt me-1"></i> すべて削除
                    </button>
                </form>
            </div>
            @endif
            <!-- Search and info section remains the same -->
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

            <!-- Table remains the same -->
            <div class="table-responsive">
                <table class="table table-striped align-middle text-nowrap">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>ID/NIM/NIP</th>
                            <th>氏名</th>
                            <th class="text-center">出席時間</th>
                            <th class="text-center">ステータス</th>
                            <th class="text-end">アクション</th>
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
                            <td class="text-end">
                                <form method="POST" action="{{ route('attendance.deleteLog', [$attendance->id, $log->id]) }}" style="display:inline;" class="form-delete-log">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="削除">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
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

            <!-- Custom Pagination - Added directly here -->
            @if($logs->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    表示中: {{ $logs->firstItem() }} - {{ $logs->lastItem() }} / 全{{ $logs->total() }}件
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Previous Page Link --}}
                        @if($logs->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link px-3">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link px-3" href="{{ $logs->previousPageUrl() }}" rel="prev">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @php
                            // Show limited page numbers
                            $start = max(1, $logs->currentPage() - 2);
                            $end = min($logs->lastPage(), $logs->currentPage() + 2);
                        @endphp

                        @if($start > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $logs->url(1) }}">1</a>
                            </li>
                            @if($start > 2)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                        @endif

                        @for($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $i == $logs->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $logs->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if($end < $logs->lastPage())
                            @if($end < $logs->lastPage() - 1)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $logs->url($logs->lastPage()) }}">{{ $logs->lastPage() }}</a>
                            </li>
                        @endif

                        {{-- Next Page Link --}}
                        @if($logs->hasMorePages())
                            <li class="page-item">
                                <a class="page-link px-3" href="{{ $logs->nextPageUrl() }}" rel="next">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link px-3">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .pagination {
        margin-bottom: 0;
    }
    .page-item.active .page-link {
        background-color: #3498db;
        border-color: #3498db;
    }
    .page-link {
        color: #3498db;
        min-width: 38px;
        text-align: center;
    }
    .page-item.disabled .page-link {
        color: #6c757d;
    }
</style>
@endsection

@section('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });

    // SweetAlert konfirmasi hapus satu log
    document.querySelectorAll('.form-delete-log').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '本当に削除しますか？',
                text: 'この出席ログを削除します。',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'はい、削除',
                cancelButtonText: 'キャンセル'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
    // SweetAlert konfirmasi hapus semua log
    document.querySelectorAll('.form-delete-all').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'すべて削除しますか？',
                text: 'すべての出席ログを削除します。',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'はい、削除',
                cancelButtonText: 'キャンセル'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // SweetAlert konfirmasi export excel
    document.querySelectorAll('.form-export-excel').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'エクスポートしますか？',
                text: '出席データをエクセルでエクスポートします。',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'はい',
                cancelButtonText: 'キャンセル'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '成功',
            text: @json(session('success')),
            confirmButtonColor: '#3085d6',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    @endif
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'エラー',
            text: @json(session('error')),
            confirmButtonColor: '#d33',
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: false
        });
    @endif
</script>
@endsection