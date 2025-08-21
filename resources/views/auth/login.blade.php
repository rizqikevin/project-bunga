@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="height: 100vh;">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5 text-center">
                            <img src="{{ asset('images/logo.png') }}" alt="JSP Logo" class="mb-2" style="max-width: 120px;">
                            <h6 class="mt-2">PT JAYA SENTOSA PLASINDO</h6>
                        </div>
                        <div class="col-md-7">
                            <h5 class="text-center mb-4">SELAMAT DATANG</h5>

                            @if ($errors->any())
                                <div class="alert alert-danger py-2">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <input type="email" name="email" class="form-control bg-primary bg-opacity-10" placeholder="Masukan Email" required>
                                </div>

                                <div class="form-group mb-3">
                                    <input type="password" name="password" class="form-control bg-primary bg-opacity-10" placeholder="Password" required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    background: #fff;
    border-radius: 8px;
}
.form-control {
    border-radius: 4px;
}
.btn-primary {
    background: #6366f1;
    border: none;
}
</style>
@endsection