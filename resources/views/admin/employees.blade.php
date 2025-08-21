@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Karyawan</h1>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Karyawan
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Karyawan</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->employee_id }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>
                                <a href="{{ route('admin.qrcode', $employee->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-qrcode"></i>
                                </a>
                                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>
@endsection