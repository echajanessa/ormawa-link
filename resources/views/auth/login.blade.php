@extends('layouts/blankLayout')

@section('title', 'Login')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{ url('/login') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">@include('_partials.macros', [
                                    'width' => 25,
                                    'withbg' => 'var(--bs-primary)',
                                ])</span>
                                <span class="app-brand-text demo text-heading fw-bold">Layanan Administrasi Organisasi
                                    Mahasiswa</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1">Selamat datang di Layanan Administrasi Organisasi Mahasiswa! 👋</h4>
                        <p class="mb-6">Masuk menggunakan akun organisasi Anda</p>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form id="loginForm" method="POST" action="{{ route('login') }}">
                            @csrf

                            {{-- Email --}}
                            <div class="mb-6">
                                <label for="email" class="form-label" :value="__('Email')">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Masukkan email" required autofocus autocomplete="username">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            {{-- Password --}}
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password" :value="__('Password')">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" required autocomplete="current-password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-8">
                                <div class="d-flex justify-content-center mt-8">

                                    @if (Route::has('password.request'))
                                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                            href="{{ route('password.request') }}">
                                            {{ __('Lupa Kata Sandi?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">{{ __('Masuk') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pastikan loading disembunyikan saat halaman dimuat
        showLoadingProg(false);

        // Event listener untuk form submit
        const form = document.getElementById('loginForm');
        form.addEventListener('submit', function() {
            showLoadingProg(true); // Tampilkan loading saat submit
        });
    });

    function showLoadingProg(show) {
        const loadingElement = document.getElementById("loadingData");
        if (show) {
            loadingElement.classList.remove("d-none"); // Tampilkan loading
        } else {
            loadingElement.classList.add("d-none"); // Sembunyikan loading
        }
    }
</script>

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK'
        });
    </script>
@endif
