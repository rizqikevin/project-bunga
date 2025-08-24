@extends('layouts.employee')

@section('content')
<div class="container-fluid">
  <h4 class="mb-4">Input Kode QR Manual</h4>

  <div class="card">
    <div class="card-body">
      <form id="manual-form">
        <div class="mb-3">
          <label for="code" class="form-label">Kode (JSP-YYYYMMDDHHMMSS-xxxxxx)</label>
          <input
            type="text"
            id="code"
            name="code"
            class="form-control"
            value="{{ $prefill ?? '' }}"
            placeholder="JSP-20250823123045-1a2b3c"
            required
          >
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
        <a href="{{ route('employee.scan') }}" class="btn btn-outline-secondary ms-2">Kembali ke Scan</a>
      </form>

      <div id="res" class="mt-3"></div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('manual-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const code = document.getElementById('code').value.trim();

  const res = await fetch('{{ route("employee.submit-attendance") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ qr_id: code }) // gunakan key qr_id
  });

  const data = await res.json();

  document.getElementById('res').innerHTML =
    `<div class="alert alert-${data.success ? 'success' : 'danger'}">${data.message}</div>`;

  if (data.success) setTimeout(() => location.href='{{ route("employee.scan") }}', 1000);
});
</script>
@endsection
