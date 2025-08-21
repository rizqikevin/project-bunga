@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Laporan Absensi</h2>
        <a href="{{ route('admin.reports.export') }}" class="btn btn-success">
            <i class="fas fa-download"></i> Export Excel
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>ID Karyawan</th>
                            <th>Nama</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                            <td>{{ $attendance->employee->employee_id }}</td>
                            <td>{{ $attendance->employee->name }}</td>
                            <td>{{ $attendance->clock_in }}</td>
                            <td>{{ $attendance->clock_out }}</td>
                            <td>
                                <span class="badge {{ $attendance->status == 'hadir' ? 'bg-success' : 
                                    ($attendance->status == 'terlambat' ? 'bg-warning' : 
                                    ($attendance->status == 'izin' ? 'bg-info' : 'bg-danger')) }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td>{{ $attendance->notes }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection