@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Riwayat Absensi</h4>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
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
                        @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d F Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</td>
                            <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>
                            <td>
                                @if($attendance->status == 'tepat_waktu')
                                    <span class="badge bg-success">Tepat Waktu</span>
                                @else
                                    <span class="badge bg-warning">Terlambat</span>
                                @endif
                            </td>
                            <td><i class="bi bi-geo-alt"></i> Kantor Pusat</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data absensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            order: [[0, 'desc']]
        });
    });
</script>
@endsection