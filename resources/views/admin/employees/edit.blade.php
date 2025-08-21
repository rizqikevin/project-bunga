@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Edit Karyawan</h2>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="employee_id" class="form-label">ID Karyawan</label>
                    <input type="text" class="form-control @error('employee_id') is-invalid @enderror" 
                           id="employee_id" name="employee_id" value="{{ old('employee_id', $employee->employee_id) }}">
                    @error('employee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $employee->name) }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email', $employee->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="position" class="form-label">Posisi</label>
                    <input type="text" class="form-control @error('position') is-invalid @enderror" 
                           id="position" name="position" value="{{ old('position', $employee->position) }}">
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.employees') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection