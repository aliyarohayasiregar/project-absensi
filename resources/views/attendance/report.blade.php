@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Absensi</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.report') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        <option value="">Semua</option>
                        <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="ontime" {{ request('status') === 'ontime' ? 'selected' : '' }}>Tepat Waktu</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nama Karyawan</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->employee->user->name }}</td>
                            <td>{{ $attendance->check_in->format('d/m/Y') }}</td>
                            <td>{{ $attendance->check_in->format('H:i:s') }}</td>
                            <td>
                                @if($attendance->is_late)
                                <span class="badge bg-danger">Terlambat</span>
                                @else
                                <span class="badge bg-success">Tepat Waktu</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $attendances->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [[ 1, "desc" ]],
            "pageLength": 25,
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endpush 