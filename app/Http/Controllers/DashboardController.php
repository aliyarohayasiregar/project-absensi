<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
            // Data untuk admin dashboard
            $totalUsers = User::where('role', 'user')->count();
            $todayAttendance = Attendance::whereDate('check_in', Carbon::today())->count();
            $lateToday = Attendance::whereDate('check_in', Carbon::today())
                ->where('is_late', true)
                ->count();
            $onTimeToday = $todayAttendance - $lateToday;

            return view('dashboard.admin', compact(
                'totalUsers',
                'todayAttendance',
                'lateToday',
                'onTimeToday'
            ));
        } else {
            // Data untuk user dashboard
            $user = auth()->user();
            $todayAttendance = Attendance::where('employee_id', $user->employee->id)
                ->whereDate('check_in', Carbon::today())
                ->first();
            
            $thisMonthAttendance = Attendance::where('employee_id', $user->employee->id)
                ->whereMonth('check_in', Carbon::now()->month)
                ->count();
            
            $thisMonthLate = Attendance::where('employee_id', $user->employee->id)
                ->whereMonth('check_in', Carbon::now()->month)
                ->where('is_late', true)
                ->count();

            return view('dashboard.user', compact(
                'todayAttendance',
                'thisMonthAttendance',
                'thisMonthLate'
            ));
        }
    }
} 