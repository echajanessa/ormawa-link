@extends('layouts/blankLayout')

@section('title', 'Login')

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <div class="card px-sm-6 px-0">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center">
                        <a href="#" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros', [
                                'width' => 25,
                                'withbg' => 'var(--bs-primary)',
                            ])</span>
                            <span class="app-brand-text demo text-heading fw-bold">Layanan Administrasi Organisasi
                                Mahasiswa</span>
                        </a>
                    </div>
                    {{-- <h4 class="mb-1">Selamat datang di Layanan Administrasi Organisasi Mahasiswa! 👋</h4>
                    <p class="mb-6">Reset kata sandi </p> --}}

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="form-label" :value="__('Email')">Email</label>
                            <input id="email" class="form-control" type="email" name="email"
                                :value="old('email', $request - > email)" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label" :value="__('Password')">Kata Sandi</label>
                            <input id="password" class="form-control" type="password" name="password" required
                                autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <label class="form-label" for="password_confirmation" :value="__('Confirm Password')">Konfirmasi
                                Kata Sandi</label>

                            <input id="password_confirmation" class="form-control" type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button class="btn btn-primary d-grid w-100">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('dashboard') }}";
                }
            });
        });
    </script>
@endif
