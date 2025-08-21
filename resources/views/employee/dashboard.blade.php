@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Dashboard Karyawan</h4>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Total Kehadiran Bulan Ini</h6>
                            <h2>{{ $monthlyAttendance }}</h2>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-calendar-check fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Keterlambatan</h6>
                            <h2>{{ $lateCount }}</h2>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-exclamation-circle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Izin/Cuti</h6>
                            <h2>{{ $leaveCount }}</h2>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-file-text fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Lembur</h6>
                            <h2>{{ $overtimeCount }}</h2>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-clock-history fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terakhir -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title">Aktivitas Terakhir</h5>
                <a href="{{ route('employee.attendance.history') }}" class="text-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentActivities as $activity)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($activity->date)->format('d F Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($activity->clock_in)->format('H:i') }}</td>
                            <td>{{ $activity->clock_out ? \Carbon\Carbon::parse($activity->clock_out)->format('H:i') : '-' }}</td>
                            <td>
                                @if($activity->status == 'tepat_waktu')
                                    <span class="badge bg-success">Tepat Waktu</span>
                                @else
                                    <span class="badge bg-warning">Terlambat</span>
                                @endif
                            </td>
                            <td><i class="bi bi-geo-alt"></i> Kantor Pusat</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: {width: 250, height: 250} }
    );
    html5QrcodeScanner.render((decodedText, decodedResult) => {
        fetch('{{ route("employee.submit-attendance") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ qr_code: decodedText })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('result').innerHTML = `
                <div class="alert alert-${data.success ? 'success' : 'danger'}">
                    ${data.message}
                </div>
            `;
        });
    });
</script>
@endsection