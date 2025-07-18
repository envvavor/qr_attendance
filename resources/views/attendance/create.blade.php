@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Buat Absensi Baru</h1>
    <form method="POST" action="{{ route('attendance.store') }}">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Judul Absensi</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="start_time" class="form-label">Waktu Mulai</label>
            <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
        </div>
        <div class="mb-3">
            <label for="end_time" class="form-label">Waktu Berakhir</label>
            <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
        </div>
        <button type="submit" class="btn btn-primary">Generate QR Code</button>
    </form>
</div>
@endsection