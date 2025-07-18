<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceLogsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $attendance;

    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
    }

    public function collection()
    {
        return $this->attendance->logs()
            ->orderBy('scan_time', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'ID/NIM/NIP',
            'Nama',
            'Waktu Absen',
            'Status'
        ];
    }

    public function map($log): array
    {
        return [
            $log->id,
            $log->user_id,
            $log->name,
            $log->scan_time->format('d/m/Y H:i:s'),
            $log->status === 'present' ? 'Hadir' : 'Terlambat'
        ];
    }
}