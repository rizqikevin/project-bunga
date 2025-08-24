@extends('layouts.employee')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Scan QR Code Absensi</h4>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Scanner QR Code</h5>
                    <div id="reader" style="width: 100%"></div>
                    <div id="result" class="mt-3"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Status Absensi Hari Ini</h5>
                    @if($todayAttendance)
                        <div class="alert alert-info mt-3">
                            <p><strong>Jam Masuk:</strong> {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}</p>
                            <p><strong>Status:</strong>
                                @if($todayAttendance->status === 'terlambat')
                                    <span class="badge bg-warning">Terlambat</span>
                                @else
                                    <span class="badge bg-success">Hadir</span>
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
(async function() {
  const resultEl = document.getElementById('result');

  function showMsg(ok, msg) {
    resultEl.innerHTML = `<div class="alert alert-${ok ? 'success' : 'danger'}">${msg}</div>`;
  }

  async function postAttendance(payload) {
    const res = await fetch('{{ route("employee.submit-attendance") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify(payload)
    });
    return res.json();
  }

  const html5QrCode = new Html5Qrcode("reader");

  // Handler setiap berhasil decode
  async function onDecoded(decodedText) {
    try {
      const data = await postAttendance({ qr_id: decodedText }); // <-- key benar: qr_id
      showMsg(data.success, data.message);
      if (data.success) setTimeout(() => location.reload(), 1500);
    } catch (e) {
      showMsg(false, 'Gagal mengirim absensi: ' + (e?.message || e));
    }
  }

  try {
    // Prefer kamera belakang
    await html5QrCode.start(
      { facingMode: { exact: "environment" } },
      { fps: 10, qrbox: { width: 250, height: 250 } },
      onDecoded,
      () => {}
    );
  } catch (e) {
    // Fallback: pilih kamera yang tersedia
    try {
      const cameras = await Html5Qrcode.getCameras();
      const backCamId = cameras.find(c => /back|rear|environment/i.test(c.label))?.id || cameras[0]?.id;
      if (!backCamId) throw new Error('No camera found');
      await html5QrCode.start(
        backCamId,
        { fps: 10, qrbox: { width: 250, height: 250 } },
        onDecoded,
        () => {}
      );
    } catch (err) {
      showMsg(false, 'Kamera tidak tersedia: ' + (err?.message || err));
    }
  }

  // Tombol menuju input manual
  const manualBtn = document.createElement('a');
  manualBtn.href = '{{ route("employee.input-code") }}';
  manualBtn.className = 'btn btn-outline-secondary mt-3';
  manualBtn.innerText = 'Ketik Kode Manual';
  document.querySelector('#reader').after(manualBtn);
})();
</script>
@endsection
