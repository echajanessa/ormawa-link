@extends('layouts/blankLayout')

@section('title', 'Forgot Password')

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Forgot Password -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-6">
                            <a href="{{ url('/login') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">@include('_partials.macros', [
                                    'width' => 25,
                                    'withbg' => 'var(--bs-primary)',
                                ])</span>
                                <span
                                    class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1">Lupa Kata Sandi? 🔒</h4>
                        <p class="mb-6">Masukkan email terdaftar untuk dikirimkan link</p>
                        <form id="formAuthentication" class="mb-6" action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label for="email" class="form-label" :value="__('Email')">Email</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Masukkan email" autofocus>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <button class="btn btn-primary d-grid w-100">{{ __('Reset Kata Sandi') }}</button>
                        </form>
                        <div class="text-center">
                            <a href="{{ url('/login') }}" class="d-flex justify-content-center">
                                <i class="bx bx-chevron-left scaleX-n1-rtl me-1"></i>
                                Kembali ke Login
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Forgot Password -->
            </div>
        </div>
    </div>
@endsection


{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
