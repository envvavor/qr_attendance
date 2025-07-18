<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            // First check if columns exist before adding them
            if (!Schema::hasColumn('attendance_logs', 'user_id')) {
                $table->string('user_id')->after('attendance_id');
            }
            
            if (!Schema::hasColumn('attendance_logs', 'name')) {
                $table->string('name')->after('user_id');
            }
            
            if (!Schema::hasColumn('attendance_logs', 'scan_time')) {
                $table->timestamp('scan_time')->after('name');
            }
            
            if (!Schema::hasColumn('attendance_logs', 'status')) {
                $table->string('status')->default('present')->after('scan_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            // Only drop columns if they exist
            if (Schema::hasColumn('attendance_logs', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('attendance_logs', 'scan_time')) {
                $table->dropColumn('scan_time');
            }
            if (Schema::hasColumn('attendance_logs', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('attendance_logs', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};