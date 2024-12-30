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

        // Force timezone dan dapatkan waktu yang benar
        config(['app.timezone' => 'Asia/Jakarta']);
        date_default_timezone_set('Asia/Jakarta');
        
        $now = now();
        $currentTime = $now->format('H:i:s');
        
        // Debug waktu yang lebih detail
        \Log::info('Debug Waktu:', [
            'raw_now' => now(),
            'formatted_now' => $now->format('Y-m-d H:i:s'),
            'current_time' => $currentTime,
            'windows_time' => exec('time /t'),
            'php_timezone' => date_default_timezone_get(),
            'app_timezone' => config('app.timezone'),
            'is_dst' => date('I'),
            'offset' => date('Z'),
        ]);

        // Cek absensi hari ini menggunakan timestamp
        $todayAttendance = Attendance::where('employee_id', $user->employee->id)
            ->whereRaw('DATE(check_in) = ?', [$now->format('Y-m-d')])
            ->first();

        if ($todayAttendance) {
            if (!$todayAttendance->check_out) {
                $todayAttendance->update([
                    'check_out' => $now,
                    'is_early_leave' => $currentTime < self::END_TIME
                ]);

                return back()->with('success', 'Absen pulang berhasil dicatat - ' . $currentTime . ' WIB');
            }

            return back()->with('error', 'Anda sudah melakukan absen masuk dan keluar hari ini');
        }

        // Absen masuk
        $isLate = $currentTime > self::START_TIME;

        // Simpan absensi dengan timestamp
        Attendance::create([
            'employee_id' => $user->employee->id,
            'check_in' => $now,
            'is_late' => $isLate
        ]);

        $message = 'Absen masuk berhasil dicatat - ' . $currentTime . ' WIB';
        if ($isLate) {
            $message .= ' (Terlambat)';
        }

        return back()->with('success', $message);
    }

    public function report(Request $request)
    {
        $query = Attendance::with('employee.user')
            ->when($request->filled('start_date'), function($q) use ($request) {
                return $q->whereDate('check_in', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function($q) use ($request) {
                return $q->whereDate('check_in', '<=', $request->end_date);
            })
            ->when($request->filled('status'), function($q) use ($request) {
                if ($request->status === 'late') {
                    return $q->where('is_late', true);
                } elseif ($request->status === 'ontime') {
                    return $q->where('is_late', false);
                }
            })
            ->latest('check_in');

        $attendances = $query->get();

        return view('attendance.report', compact('attendances'));
    }
} 