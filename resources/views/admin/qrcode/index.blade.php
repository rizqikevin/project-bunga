@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>QR Code Absensi</h2>
    
    <div class="card">
        <div class="card-body text-center">
            <h3>QR Code Absensi {{ Carbon\Carbon::now()->format('d M Y') }}</h3>
            
            <div id="qrcode-container">
                @if($qrCode)
                    {!! QrCode::size(300)->generate($qrCode->qr_id) !!}
                @else
                    <p>No QR Code available</p>
                @endif
            </div>
            
            <p class="mt-3">QR Code akan berubah dalam <span id="timer">{{ $timeLeft }}</span> detik</p>
            
            <form action="{{ route('admin.qrcode.generate') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-primary">Generate QR Code Baru</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let timer = {{ $timeLeft }};
    const timerElement = document.getElementById('timer');
    
    function updateTimer() {
        if (timer > 0) {
            timer--;
            timerElement.textContent = timer;
            if (timer === 0) {
                location.reload();
            }
        }
    }

    // Mulai timer
    const interval = setInterval(updateTimer, 1000);
</script>
@endpush
@endsection