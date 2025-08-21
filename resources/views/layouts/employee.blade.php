<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Karyawan - PT JAYA SENTOSA PLASINDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .sidebar {
            background-color: #4834d4;
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: white;
            padding: 15px 20px;
            margin: 5px 0;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
    </style>
    <!-- Di bagian head, tambahkan CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
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
                    <a href="{{ route('employee.dashboard') }}" class="nav-link {{ Request::is('karyawan/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('employee.scan') }}" class="nav-link {{ Request::is('karyawan/scan') ? 'active' : '' }}">
                        <i class="bi bi-qr-code-scan"></i> QR Scanner
                    </a>
                    <a href="{{ route('employee.attendance.history') }}" class="nav-link {{ Request::is('karyawan/attendance/history') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Riwayat Absensi
                    </a>
                    <a href="{{ route('employee.profile') }}" class="nav-link {{ Request::is('karyawan/profile') ? 'active' : '' }}">
                        <i class="bi bi-person"></i> Informasi Akun
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
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    <!-- Di bagian bawah sebelum closing body, tambahkan JS DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
</body>
</html>