<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Karyawan - PT JAYA SENTOSA PLASINDO</title>
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
            display: flex;
            align-items: center;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .search-container {
            position: relative;
            max-width: 300px;
        }
        .search-container i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
        .search-input {
            padding-left: 35px;
        }
        .status-aktif {
            background-color: #e1f6e1;
            color: #25a525;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        .status-nonaktif {
            background-color: #ffe5e5;
            color: #ff4444;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
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
                    <a href="{{ route('admin.employees') }}" class="nav-link active">
                        <i class="bi bi-people"></i> Kelola Karyawan
                    </a>
                    <a href="{{ route('admin.qrcode.index') }}" class="nav-link">
                        <i class="bi bi-qr-code"></i> Kelola QR Code
                    </a>
                    <a href="#" class="nav-link">
                        <i class="bi bi-file-text"></i> Laporan Absensi
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-auto">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Kelola Data Karyawan</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                        + Tambah Karyawan
                    </button>
                </div>

                <!-- Search and Filter -->
                <div class="d-flex justify-content-between mb-4">
                    <div class="search-container">
                        <i class="bi bi-search"></i>
                        <input type="text" class="form-control search-input" placeholder="Cari karyawan...">
                    </div>
                    <button class="btn btn-outline-secondary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>

                <!-- Employee Table -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID Karyawan</th>
                                <th>Nama Lengkap</th>
                                <th>Jabatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $index => $employee)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $employee->employee_id }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $employee->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal Tambah Karyawan -->
                <div class="modal fade" id="addEmployeeModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Karyawan Baru</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('admin.employees.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">ID Karyawan</label>
                                        <input type="text" name="employee_id" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jabatan</label>
                                        <input type="text" name="position" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Karyawan -->
    @foreach($employees as $employee)
    <div class="modal fade" id="editModal{{ $employee->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Karyawan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ID Karyawan</label>
                            <input type="text" name="employee_id" class="form-control" value="{{ $employee->employee_id }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ $employee->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="position" class="form-control" value="{{ $employee->position }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>