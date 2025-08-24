@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>QR Code Absensi</h2>

    <div class="card">
        <div class="card-body text-center">
            <h3>QR Code Absensi {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d M Y') }}</h3>

            <div id="qrcode-container" class="d-flex justify-content-center" style="min-height: 360px;">
                {!! $qrSvg !!} {{-- SVG dikirim dari controller --}}
            </div>

            <p class="mt-3">
                QR Code akan berubah dalam
                <span id="timer">{{ $timeLeft }}</span> detik
            </p>

            <!-- Tombol manual (AJAX, opsional) -->
            <button id="btn-refresh" type="button" class="btn btn-outline-primary mt-2">
                Generate QR Code Baru
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    let timer = {{ $timeLeft }};
    const timerEl   = document.getElementById('timer');
    const container = document.getElementById('qrcode-container');
    const btn       = document.getElementById('btn-refresh');
    const REFRESH_URL = "{{ route('admin.qrcode.refresh') }}";

    async function refreshQR() {
        try {
            const res  = await fetch(REFRESH_URL, { cache: 'no-store' });
            const data = await res.json();
            // konsisten: controller kirim "qrSvg"
            container.innerHTML = data.qrSvg;
            timer = data.timeLeft || 60;
            timerEl.textContent = timer;
        } catch (e) {
            console.error('Gagal refresh QR:', e);
        }
    }

    function tick() {
        if (timer > 0) {
            timer--;
            timerEl.textContent = timer;
        } else {
            refreshQR();
        }
    }

    // Mulai detik
    setInterval(tick, 1000);

    // Saat tab kembali aktif, sinkronkan QR
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) refreshQR();
    });

    // Tombol manual
    btn.addEventListener('click', refreshQR);
})();
</script>
@endpush
