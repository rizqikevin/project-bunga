@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Scan QR Code Absensi</h4>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Scanner QR Code</h5>
                    <div id="reader" style="width: 100%"></div>
                    <div id="result" class="mt-3"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Status Absensi Hari Ini</h5>
                    @if($todayAttendance)
                        <div class="alert alert-info mt-3">
                            <p><strong>Jam Masuk:</strong> {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}</p>
                            <p><strong>Status:</strong> 
                                @if($todayAttendance->status == 'tepat_waktu')
                                    <span class="badge bg-success">Tepat Waktu</span>
                                @else
                                    <span class="badge bg-warning">Terlambat</span>
                                @endif
                            </p>
                            @if(!$todayAttendance->clock_out)
                                <form action="{{ route('employee.clock-out') }}" method="POST" class="mt-3">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">Clock Out</button>
                                </form>
                            @else
                                <p><strong>Jam Keluar:</strong> {{ \Carbon\Carbon::parse($todayAttendance->clock_out)->format('H:i') }}</p>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-warning mt-3">
                            Anda belum melakukan absensi hari ini
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
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
            if(data.success) {
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        });
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: { width: 250, height: 250 } }
    );
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection