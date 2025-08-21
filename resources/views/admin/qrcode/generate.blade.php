@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">QR Code {{ $employee->name }}</h5>
                </div>
                <div class="card-body text-center">
                    <div class="qr-code-container mb-3">
                        {!! $qrcode !!}
                    </div>
                    <div class="employee-info">
                        <p><strong>ID Karyawan:</strong> {{ $employee->employee_id }}</p>
                        <p><strong>Nama:</strong> {{ $employee->name }}</p>
                        <p><strong>Posisi:</strong> {{ $employee->position }}</p>
                    </div>
                    <a href="{{ route('admin.qrcode') }}" class="btn btn-secondary">Kembali</a>
                    <button onclick="window.print()" class="btn btn-primary">Cetak QR Code</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection