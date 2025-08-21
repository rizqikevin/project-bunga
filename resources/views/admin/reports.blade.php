@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Absensi</h1>
        <a href="{{ route('admin.reports.export') }}" class="btn btn-success">
            <i class="fas fa-download"></i> Export Excel
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('admin.reports') }}" method="GET" class="form-inline">
                <div class="form-group mx-sm-3 mb-2">
                    <label class="mr-2">Periode:</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    <span class="mx-2">sampai</span>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Filter</button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Karyawan</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->date }}</td>
                            <td>{{ $attendance->employee->name }}</td>
                            <td>{{ $attendance->clock_in }}</td>
                            <td>{{ $attendance->clock_out }}</td>
                            <td>
                                @if($attendance->status == 'hadir')
                                    <span class="badge badge-success">Hadir</span>
                                @elseif($attendance->status == 'terlambat')
                                    <span class="badge badge-warning">Terlambat</span>
                                @else
                                    <span class="badge badge-danger">{{ $attendance->status }}</span>
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
@endsection