@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h1 class="h3 fw-bold text-primary">Daftar Sesi Absensi</h1>
        <a href="{{ route('attendance.create') }}" class="btn btn-primary mt-3 mt-md-0">
            <i class="fas fa-plus me-2"></i> Buat Sesi Baru
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle text-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Waktu</th>
                            <th class="text-center">Peserta</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        @php
                            $status = now() < $attendance->start_time ? 'upcoming' : 
                                     (now() > $attendance->end_time ? 'completed' : 'ongoing');
                            $statusLabel = [
                                'upcoming' => ['label' => 'Akan Datang', 'class' => 'warning'],
                                'ongoing' => ['label' => 'Berlangsung', 'class' => 'success'],
                                'completed' => ['label' => 'Selesai', 'class' => 'danger']
                            ][$status];
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}</td>
                            <td class="fw-semibold">{{ $attendance->title }}</td>
                            <td>
                                <div class="text-muted small">Mulai: {{ $attendance->start_time->format('d M Y H:i') }}</div>
                                <div class="text-muted small">Selesai: {{ $attendance->end_time->format('d M Y H:i') }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary text-white">{{ $attendance->logs_count }} Peserta</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $statusLabel['class'] }}">
                                    {{ $statusLabel['label'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('attendance.logs', $attendance->id) }}" 
                                   class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="tooltip" title="Lihat Daftar Hadir">
                                    <i class="fas fa-list"></i>
                                </a>
                                <a href="{{ route('attendance.show', $attendance->id) }}" 
                                   class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Lihat QR Code">
                                    <i class="fas fa-qrcode"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-1">Belum ada sesi absensi</p>
                                    <a href="{{ route('attendance.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Buat Sesi Baru
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attendances->hasPages())
            <div class="card-footer bg-white d-flex justify-content-center py-3">
                {{ $attendances->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });
</script>
@endsection
