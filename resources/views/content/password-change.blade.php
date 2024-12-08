@extends('layouts/blankLayout')

@section('title', 'Ubah Kata Sandi')

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

                    <form method="POST" action="{{ route('change-password.store') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-4 form-password-toggle">
                            <label for="current_password" class="form-label">Kata Sandi Lama</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="current_password" class="form-control" name="current_password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" required autocomplete="current_password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('current_password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4 form-password-toggle">
                            <label for="new_password" class="form-label">Kata Sandi Baru</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="new_password" class="form-control" name="new_password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" required autocomplete="new-password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="new_password_confirmation">Konfirmasi
                                Kata Sandi</label>

                            <div class="input-group input-group-merge">
                                <input type="password" id="new_password_confirmation" class="form-control"
                                    name="new_password_confirmation"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" required autocomplete="new-password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>

                            @error('new_password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="btn btn-primary d-grid w-100">
                                {{ __('Simpan Perubahan') }}
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
                    window.location.href = "{{ route('login') }}";
                }
            });
        });
    </script>
@endif
