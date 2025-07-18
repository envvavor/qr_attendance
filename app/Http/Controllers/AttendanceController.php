<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Exports\AttendanceLogsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    // Halaman untuk generate QR
    public function create()
    {
        return view('attendance.create');
    }

    // Simpan data absensi dan generate QR
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Convert string dates to DateTime objects
        $startTime = \Carbon\Carbon::parse($validated['start_time']);
        $endTime = \Carbon\Carbon::parse($validated['end_time']);

        // Create attendance record
        $attendance = Attendance::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'qr_code' => '' // Temporary empty value
        ]);

        // Generate QR code
        $url = route('attendance.scan', $attendance->id);
        $qrCode = QrCode::size(300)->generate($url);
        
        $attendance->update(['qr_code' => $qrCode]);

        return redirect()->route('attendance.show', $attendance->id)
            ->with('success', 'Absensi berhasil dibuat');
    }
    
    // Tampilkan QR code
    public function show(Attendance $attendance)
    {
        return view('attendance.show', compact('attendance'));
    }

    // Halaman scan QR untuk user
    public function scan(Attendance $attendance)
    {
        $now = now();
        $status = $this->getAttendanceStatus($attendance, $now);
        
        return view('attendance.status', [
            'attendance' => $attendance,
            'status' => $status,
            'current_time' => $now->format('d M Y H:i'),
        ]);
    }

    private function getAttendanceStatus($attendance, $currentTime)
    {
        if ($currentTime < $attendance->start_time) {
            return [
                'type' => 'not_open',
                'message' => '参加はまだ開始されていません',
                'details' => '出席は次の場合に行われます: ' . $attendance->start_time->format('d M Y H:i')
            ];
        }
        
        if ($currentTime > $attendance->end_time) {
            return [
                'type' => 'closed',
                'message' => '参加は締め切られました',
                'details' => '出席は次の場合に行われます:' . 
                    $attendance->start_time->format('d M Y H:i') . ' - ' . 
                    $attendance->end_time->format('d M Y H:i')
            ];
        }
        
        return [
            'type' => 'open',
            'message' => '出席開始中',
            'details' => '出席してください'
        ];
    }

    // Proses absensi
    public function processAttendance(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'user_id' => 'required|string|max:50',
            'name' => 'required|string|max:100',
        ]);

        if ($this->isDuplicateAttendance($attendance->id, $validated['user_id'])) {
            return response()->json([
                'message' => 'このIDは既に出席済みです'
            ], 422);
        }

        $log = $attendance->logs()->create([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'scan_time' => now(),
            'status' => 'present'
        ]);

        return response()->json([
            'success' => true,
            'message' => '出席が記録されました',
            'user_id' => $validated['user_id'],
            'name' => $validated['name']
        ]);
    }

    private function isDuplicateAttendance($attendanceId, $userId)
    {
        return AttendanceLog::where('attendance_id', $attendanceId)
                        ->where('user_id', $userId)
                        ->exists();
    }

    public function scanForm(Attendance $attendance)
    {
        // Only allow if within time window
        $now = now();
        if ($now < $attendance->start_time || $now > $attendance->end_time) {
            return redirect()->route('attendance.scan', $attendance->id);
        }

        return view('attendance.scan', compact('attendance'));
    }

    public function logs(Attendance $attendance)
    {
        $logs = $attendance->logs()
            ->when(request('search'), function($query) {
                $query->where(function($q) {
                    $q->where('user_id', 'like', '%'.request('search').'%')
                    ->orWhere('name', 'like', '%'.request('search').'%');
                });
            })
            ->orderBy('scan_time', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('attendance.logs', compact('attendance', 'logs'));
    }

    public function export(Attendance $attendance)
    {
        $filename = 'absensi-' . Str::slug($attendance->title) . '-' . now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new AttendanceLogsExport($attendance), $filename);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $attendances = Attendance::withCount('logs')
            ->when($search, function($query) use ($search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        return view('attendance.index', compact('attendances'));
    }

    public function success(Request $request, Attendance $attendance)
    {
        $data = json_decode($request->attendance_data, true);
        
        return back()
            ->with('success', '出席が正常に記録されました')
            ->with('user_id', $data['user_id'] ?? '')
            ->with('name', $data['name'] ?? '')
            ->with('scan_time', now()->format('Y-m-d H:i:s'));
    }
}