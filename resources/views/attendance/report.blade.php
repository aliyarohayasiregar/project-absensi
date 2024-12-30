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
            <form method="GET" action="{{ route('attendance.report') }}" class="row g-3" id="filterForm">
                <div class="col-md-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" id="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Terlambat</option>
                        <option value="ontime" {{ request('status') === 'ontime' ? 'selected' : '' }}>Tepat Waktu</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('attendance.report') }}" class="btn btn-secondary">Reset</a>
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
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let table = $('#dataTable').DataTable({
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

    // Date range validation
    $('#start_date, #end_date').change(function() {
        let startDate = $('#start_date').val();
        let endDate = $('#end_date').val();
        
        if(startDate && endDate) {
            if(startDate > endDate) {
                alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir');
                $(this).val('');
            }
        }
    });

    // Auto submit on status change
    $('#statusFilter').change(function() {
        $('#filterForm').submit();
    });
});
</script>
@endpush
@endsection 