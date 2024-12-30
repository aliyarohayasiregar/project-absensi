@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Absensi</h1>
        @if(auth()->user()->role !== 'admin')
        <div>
            <div class="h5 mb-0 font-weight-bold text-gray-800">
                {{ now()->format('l, d F Y') }}
                <span class="ms-3" id="clock">{{ now()->format('H:i:s') }}</span>
            </div>
            <form action="{{ route('attendance.store') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-clock"></i> 
                    @if(!$todayAttendance)
                        Absen Masuk
                    @elseif(!$todayAttendance->check_out)
                        Absen Pulang
                    @else
                        Sudah Absen
                    @endif
                </button>
            </form>
        </div>
        @endif
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            @if(auth()->user()->role === 'admin')
                            <th>Nama Karyawan</th>
                            @endif
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Status Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            @if(auth()->user()->role === 'admin')
                            <td>{{ $attendance->employee->user->name }}</td>
                            @endif
                            <td>{{ $attendance->check_in->format('d/m/Y') }}</td>
                            <td>{{ $attendance->check_in->format('H:i:s') }}</td>
                            <td>
                                @if($attendance->is_late)
                                <span class="badge bg-danger">Terlambat</span>
                                @else
                                <span class="badge bg-success">Tepat Waktu</span>
                                @endif
                            </td>
                            <td>{{ $attendance->check_out ? $attendance->check_out->format('H:i:s') : '-' }}</td>
                            <td>
                                @if(!$attendance->check_out)
                                <span class="badge bg-warning">Belum Absen Pulang</span>
                                @elseif($attendance->is_early_leave)
                                <span class="badge bg-danger">Pulang Cepat</span>
                                @else
                                <span class="badge bg-success">Tepat Waktu</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update jam real-time
    function updateClock() {
        const clockElement = document.getElementById('clock');
        if (clockElement) {
            const now = new Date();
            clockElement.textContent = now.toLocaleTimeString('id-ID');
        }
    }

    setInterval(updateClock, 1000);

    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [[ 1, "desc" ]],
            "pageLength": 25,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
            },
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endpush 