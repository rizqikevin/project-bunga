@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Informasi Akun</h4>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-circle mb-3">
                            <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                        </div>
                        <h5>{{ Auth::user()->name }}</h5>
                        <p class="text-muted">{{ Auth::user()->position }}</p>
                    </div>

                    <div class="mb-3 border-bottom pb-3">
                        <label class="text-muted">ID Karyawan</label>
                        <div class="fw-bold">{{ Auth::user()->employee_id }}</div>
                    </div>

                    <div class="mb-3 border-bottom pb-3">
                        <label class="text-muted">Nama Lengkap</label>
                        <div class="fw-bold">{{ Auth::user()->name }}</div>
                    </div>

                    <div class="mb-3 border-bottom pb-3">
                        <label class="text-muted">Jabatan</label>
                        <div class="fw-bold">{{ Auth::user()->position }}</div>
                    </div>

                    <div class="mb-3 border-bottom pb-3">
                        <label class="text-muted">Email</label>
                        <div class="fw-bold">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-key"></i> Ubah Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('employee.update-password') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
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
@endsection