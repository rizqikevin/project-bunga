<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi - PT JAYA SENTOSA PLASINDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .sidebar {
            background-color: #0d6efd;
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: white;
            padding: 15px 20px;
            margin: 5px 0;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h5>PT JAYA SENTOSA PLASINDO</h5>
                </div>
                <div class="nav flex-column">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="bi bi-house-door"></i> Beranda
                    </a>
                    <a href="{{ route('admin.employees') }}" class="nav-link">
                        <i class="bi bi-people"></i> Kelola Karyawan
                    </a>
                    <a href="{{ route('admin.qrcode.index') }}" class="nav-link">
                        <i class="bi bi-qr-code"></i> Kelola QR Code
                    </a>
                    <a href="{{ route('admin.attendance.report') }}" class="nav-link active">
                        <i class="bi bi-file-text"></i> Laporan Absensi
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h4 class="mb-4">Laporan Absensi Karyawan</h4>

                <!-- Filter Form -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('admin.attendance.report') }}" method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Karyawan</label>
                                <select name="employee_id" class="form-control">
                                    <option value="">Semua Karyawan</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->employee_id }}" {{ request('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                                            {{ $employee->name }} ({{ $employee->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
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
                                        <td>{{ date('d/m/Y', strtotime($attendance->date)) }}</td>
                                        <td>{{ $attendance->user->employee_id }}</td>
                                        <td>{{ $attendance->user->name }}</td>
                                        <td>{{ date('H:i', strtotime($attendance->clock_in)) }}</td>
                                        <td>{{ $attendance->clock_out ? date('H:i', strtotime($attendance->clock_out)) : '-' }}</td>
                                        <td>
                                            @if($attendance->status == 'terlambat')
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>