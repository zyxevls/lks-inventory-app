@extends('layouts.app')

@section('content')
<section class="min-vh-100 d-flex align-items-center bg-body-tertiary py-3 py-lg-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="row g-0">
                        <div class="col-12 col-lg-6 bg-white p-4 p-lg-5 d-flex flex-column justify-content-center">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-primary text-white rounded-3 d-inline-flex align-items-center justify-content-center fw-bold p-3">
                                    <i class="bi bi-box-seam fs-4"></i>
                                </div>
                                <div>
                                    <p class="text-uppercase text-secondary small fw-semibold mb-1">{{ config('app.name', 'Inventory App') }}</p>
                                </div>
                            </div>

                            <form id="loginForm" class="needs-validation" action="{{ route('login.post') }}" method="POST" novalidate>
                                @csrf

                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                                        <input
                                            type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            placeholder="contoh@email.com"
                                            autocomplete="username"
                                            required
                                            autofocus>
                                        @error('email')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                        @else
                                        <div class="invalid-feedback">
                                            Email wajib diisi dan format harus valid.
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">Kata sandi</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                        <input
                                            type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            id="password"
                                            name="password"
                                            placeholder="Masukkan kata sandi"
                                            autocomplete="current-password"
                                            required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Tampilkan kata sandi">
                                            <i class="bi bi-eye me-1" id="togglePasswordIcon"></i>
                                        </button>
                                        @error('password')
                                        <div class="invalid-feedback d-block w-100">
                                            {{ $message }}
                                        </div>
                                        @else
                                        <div class="invalid-feedback">
                                            Kata sandi wajib diisi.
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">Ingat saya</label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">
                                    Masuk ke Dashboard
                                </button>
                            </form>
                        </div>

                        <div class="col-12 col-lg-6 bg-primary bg-gradient text-white d-flex flex-column justify-content-between p-4 p-lg-5">
                            <div>
                                <span class="badge text-bg-light text-primary rounded-pill px-3 py-2 mb-3">Login</span>
                            </div>

                            <div class="my-4">
                                <img src="{{ asset('images/login-illustration.svg') }}" alt="Ilustrasi dashboard login modern" class="img-fluid rounded-4 shadow-lg w-100 object-fit-cover">
                            </div>

                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="card bg-white bg-opacity-10 border-0 text-center text-white h-100">
                                        <div class="card-body py-3 px-2">
                                            <div class="fw-bold">99.9%</div>
                                            <small class="text-white-50">stabil</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="card bg-white bg-opacity-10 border-0 text-center text-white h-100">
                                        <div class="card-body py-3 px-2">
                                            <div class="fw-bold">24/7</div>
                                            <small class="text-white-50">siap pakai</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="card bg-white bg-opacity-10 border-0 text-center text-white h-100">
                                        <div class="card-body py-3 px-2">
                                            <div class="fw-bold">1 klik</div>
                                            <small class="text-white-50">masuk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const toggleButton = document.getElementById('togglePassword');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        const passwordInput = document.getElementById('password');

        if (toggleButton && passwordInput && toggleIcon) {
            toggleButton.addEventListener('click', function() {
                const hidden = passwordInput.type === 'password';
                passwordInput.type = hidden ? 'text' : 'password';
                toggleIcon.className = hidden ? 'bi bi-eye-slash me-1' : 'bi bi-eye me-1';
                toggleButton.setAttribute('aria-label', hidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
                passwordInput.focus();
            });
        }

        if (form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            });
        }
    });
</script>
@endpush
@endsection