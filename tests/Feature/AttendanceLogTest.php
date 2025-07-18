<?php

namespace Tests\Feature;

use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttendanceLogTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // tests/Feature/AttendanceLogTest.php
    public function test_attendance_log_creation()
    {
        $attendance = Attendance::factory()->create();
        
        $response = $this->postJson(route('attendance.process', $attendance->id), [
            'user_id' => '12345',
            'name' => 'John Doe'
        ]);

        $response->assertStatus(200)
                ->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('attendance_logs', [
            'attendance_id' => $attendance->id,
            'user_id' => '12345'
        ]);
    }
}
