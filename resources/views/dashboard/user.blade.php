@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Status Absensi Hari Ini -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Status Absensi Hari Ini</h5>
                    @if($todayAttendance)
                        <div class="alert alert-success">
                            Anda sudah absen pada {{ $todayAttendance->check_in->format('H:i:s') }}
                            @if($todayAttendance->is_late)
                                <span class="badge bg-warning">Terlambat</span>
                            @else
                                <span class="badge bg-success">Tepat Waktu</span>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Anda belum absen hari ini
                            <form action="{{ route('attendance.store') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm ms-2">Absen Sekarang</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Total Kehadiran Bulan Ini -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Kehadiran Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $thisMonthAttendance }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keterlambatan Bulan Ini -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Keterlambatan Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $thisMonthLate }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Persentase Kehadiran -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Persentase Tepat Waktu</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        {{ $thisMonthAttendance > 0 ? round(($thisMonthAttendance - $thisMonthLate) / $thisMonthAttendance * 100) : 0 }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 