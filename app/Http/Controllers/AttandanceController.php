<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttandanceController extends Controller
{
    public function index()
    {
        // Hanya admin yang bisa melihat semua data
        if (auth()->user()->role !== 'admin') {
            $attendance = Attendance::with('employee')
                ->where('employee_id', auth()->user()->employee->id)
                ->get();
        } else {
            $attendance = Attendance::with('employee')->get();
        }

        return response()->json([
            'message' => 'Data absensi berhasil diambil',
            'data' => $attendance
        ]);
    }

    public function store(Request $request)
    {
        // Hanya user biasa yang bisa absen
        if (auth()->user()->role === 'admin') {
            return response()->json([
                'message' => 'Admin tidak dapat melakukan absensi'
            ], 403);
        }

        $employee_id = auth()->user()->employee->id;

        // Check if employee already checked in today
        $existing = Attendance::where('employee_id', $employee_id)
            ->whereDate('check_in', today())
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Anda sudah absen hari ini'
            ], 422);
        }

        // Cek keterlambatan
        $now = Carbon::now();
        $isLate = $now->format('H:i') > '08:00';

        $attendance = Attendance::create([
            'employee_id' => $employee_id,
            'check_in' => $now,
            'is_late' => $isLate
        ]);

        return response()->json([
            'message' => 'Absensi berhasil dicatat',
            'data' => $attendance
        ], 201);
    }

    public function getLateAttendances()
    {
        // Hanya admin yang bisa mengakses
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $lateAttendances = Attendance::with('employee')
            ->where('is_late', true)
            ->get();

        return response()->json([
            'data' => $lateAttendances
        ]);
    }
} 