@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
    @vite(['resources/assets/js/pages-account-settings-account.js'])
@endsection

<style>
    .swal2-container {
        z-index: 10000 !important;
    }
</style>

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <ul class="nav nav-pills flex-column flex-md-row mb-6">
                    <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                                class="bx bx-sm bx-user me-1_5"></i> Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/ubah-sandi') }}"><i
                                class="bx bx-sm bx-lock-open-alt me-1_5"></i> Kata Sandi</a></li>
                </ul>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update', $user->id) }}" onsubmit="return handleFormSubmit(event)"
                class="mt-2 space-y-6">
                @csrf
                @method('PUT')

                <div class="card mb-6">
                    <!-- Account -->
                    <div class="card-body">

                    </div>
                    <div class="card-body pt-4">
                        <div class="row g-6">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control" type="text" id="email" value="{{ $user->email }}"
                                    disabled />
                            </div>
                            <div class="col-md-6">
                                <label for="role_id" class="form-label">Role</label>
                                <input class="form-control" type="text" id="role_id"
                                    value="{{ $user->userRole->role_name }}" disabled />
                            </div>
                            <div class="col-md-6">
                                <label for="organization_id" class="form-label">Organisasi</label>
                                <input class="form-control" type="text" id="organization_id"
                                    value="{{ $user->organization->org_name ?? '-' }}" disabled />
                            </div>
                            <div class="col-md-6">
                                <label for="faculty_id" class="form-label">Fakultas</label>
                                <input class="form-control" type="text" id="faculty_id"
                                    value="{{ $user->faculty->faculty_name ?? '-' }}" disabled />
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama</label>
                                <input class="form-control" type="text" id="name" name="name"
                                    value="{{ old('name', $user->name) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="phone_number">Nomor Handphone</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">ID (+62)</span>
                                    <input type="text" id="phone_number" name="phone_number" class="form-control"
                                        placeholder="812 2345 7890" value="{{ old('phone_number', $user->phone_number) }}"
                                        required />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3">Save changes</button>
                            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /Account -->
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
                    window.location.href = "{{ route('profile.edit') }}";
                }
            });
        });
    </script>
@endif
