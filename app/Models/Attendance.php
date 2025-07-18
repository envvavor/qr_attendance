<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'qr_code',
        'start_time',
        'end_time',
        'attendance_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function isOpen()
    {
        $now = now();
        return $now >= $this->start_time && $now <= $this->end_time;
    }

    public function isNotOpenYet()
    {
        return now() < $this->start_time;
    }

    public function isClosed()
    {
        return now() > $this->end_time;
    }

    public function timeStatus()
    {
        if ($this->isNotOpenYet()) {
            return 'not_open';
        }
        
        if ($this->isClosed()) {
            return 'closed';
        }
        
        return 'open';
    }

    public function logs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'attendance_logs', 'attendance_id', 'user_id')
                ->withPivot(['scan_time', 'status'])
                ->withTimestamps();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('start_time', '<=', now())
                    ->where('end_time', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('end_time', '<', now());
    }
}