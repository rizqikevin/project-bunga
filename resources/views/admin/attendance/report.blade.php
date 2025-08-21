@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Laporan Absensi Karyawan</h2>
    
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate ?? '2023-08-01' }}">
                </div>
                <div class="col-md-3">
                    <label>Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? now()->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label>Karyawan</label>
                    <select name="employee" class="form-control">
                        <option value="all">Semua Karyawan</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $selectedEmployee == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>ID Karyawan</th>
                            <th>Nama</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                            <td>{{ $attendance->user->employee_id }}</td>
                            <td>{{ $attendance->user->name }}</td>
                            <td>{{ $attendance->time_in }}</td>
                            <td>{{ $attendance->time_out ?? '-' }}</td>
                            <td>
                                @if($attendance->status == 'Terlambat')
                                    <span class="text-danger">{{ $attendance->status }}</span>
                                @else
                                    <span class="text-success">{{ $attendance->status }}</span>
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