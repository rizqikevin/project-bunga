@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Dashboard Admin</h2>
    
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Karyawan</h5>
                    <h2>{{ $totalEmployees }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Absensi Hari Ini</h5>
                    <h2>{{ $todayAttendance }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Terlambat Hari Ini</h5>
                    <h2>{{ $lateToday }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Absensi Terbaru</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentAttendances as $attendance)
                    <tr>
                        <td>{{ $attendance->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                        <td>{{ $attendance->time_in }}</td>
                        <td>{{ $attendance->time_out ?? '-' }}</td>
                        <td>{{ $attendance->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection