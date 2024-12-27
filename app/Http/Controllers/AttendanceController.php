<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    const START_TIME = '09:00';
    const END_TIME = '18:00';

    public function index()
    {
        Carbon::setLocale('id');
        
        $user = auth()->user();
        if ($user->role === 'admin') {
            $attendances = Attendance::with('employee.user')
                ->latest('check_in')
                ->paginate(10);
        } else {
            $attendances = Attendance::where('employee_id', $user->employee->id)
                ->latest('check_in')
                ->paginate(10);
        }

        $todayAttendance = null;
        if ($user->role !== 'admin') {
            $todayAttendance = Attendance::where('employee_id', $user->employee->id)
                ->whereRaw('DATE(CONVERT_TZ(check_in, "+00:00", "+07:00")) = CURDATE()')
                ->first();
        }

        return view('attendance.index', compact('attendances', 'todayAttendance'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin tidak dapat melakukan absensi');
        }

        // Ambil waktu server dalam WIB menggunakan CONVERT_TZ
        $serverTime = DB::select("SELECT CONVERT_TZ(NOW(), '+00:00', '+07:00') as now")[0]->now;
        $currentTime = date('H:i', strtotime($serverTime));
        
        // Debug waktu
        \Log::info('Server times:', [
            'raw_now' => DB::select('SELECT NOW() as now')[0]->now,
            'converted_now' => $serverTime,
            'current_time' => $currentTime
        ]);
        
        // Cek apakah sudah absen masuk hari ini
        $todayAttendance = Attendance::where('employee_id', $user->employee->id)
            ->whereRaw('DATE(CONVERT_TZ(check_in, "+00:00", "+07:00")) = CURDATE()')
            ->first();

        if ($todayAttendance) {
            if (!$todayAttendance->check_out) {
                DB::table('attendances')
                    ->where('id', $todayAttendance->id)
                    ->update([
                        'check_out' => DB::raw('CONVERT_TZ(NOW(), "+00:00", "+07:00")'),
                        'is_early_leave' => $currentTime < self::END_TIME,
                        'updated_at' => DB::raw('CONVERT_TZ(NOW(), "+00:00", "+07:00")')
                    ]);

                return back()->with('success', 'Absen pulang berhasil dicatat - ' . $currentTime . ' WIB');
            }

            return back()->with('error', 'Anda sudah melakukan absen masuk dan keluar hari ini');
        }

        // Absen masuk
        $isLate = $currentTime > self::START_TIME;
        
        DB::statement("
            INSERT INTO attendances (employee_id, check_in, is_late, created_at, updated_at)
            VALUES (?, CONVERT_TZ(NOW(), '+00:00', '+07:00'), ?, 
                   CONVERT_TZ(NOW(), '+00:00', '+07:00'), 
                   CONVERT_TZ(NOW(), '+00:00', '+07:00'))
        ", [$user->employee->id, $isLate]);

        $message = 'Absen masuk berhasil dicatat - ' . $currentTime . ' WIB';
        if ($isLate) {
            $message .= ' (Terlambat)';
        }

        return back()->with('success', $message);
    }

    public function report()
    {
        $attendances = Attendance::with('employee.user')
            ->when(request('date'), function($query) {
                return $query->whereDate('check_in', request('date'));
            })
            ->when(request('status'), function($query) {
                if (request('status') === 'late') {
                    return $query->where('is_late', true);
                } elseif (request('status') === 'early_leave') {
                    return $query->where('is_early_leave', true);
                }
                return $query;
            })
            ->latest('check_in')
            ->paginate(10);

        return view('attendance.report', compact('attendances'));
    }
} 