@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Tambah Karyawan</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.employees.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="employee_id" class="form-label">ID Karyawan</label>
                    <input type="text" class="form-control @error('employee_id') is-invalid @enderror" 
                           id="employee_id" name="employee_id" value="{{ old('employee_id') }}">
                    @error('employee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="position" class="form-label">Posisi</label>
                    <input type="text" class="form-control @error('position') is-invalid @enderror" 
                           id="position" name="position" value="{{ old('position') }}">
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.employees') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection