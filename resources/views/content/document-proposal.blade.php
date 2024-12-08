@php
    $isNavbar = false;
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Proposal Kegiatan')

@section('vendor-script')
    @vite('resources/assets/vendor/libs/custom-date/custom-date.js') <!-- Memanggil file JavaScript melalui Vite -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Menambahkan SweetAlert -->
@endsection

<style>
    .swal2-container {
        z-index: 10000 !important;
    }
</style>

@section('content')
    <ul class="navbar-nav align-items-end mb-6 me-3">
        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <div class="align-items-center row g-0">
                <div class="col-sm-9 text-end">
                    <div class="flex-grow-1">
                        <h6 class="mb-0 me-2">{{ Auth::user()->name }}</h6>
                    </div>
                </div>
                <div class="col-3 col-md-3 justify-content-center d-flex">
                    <a class="nav-link dropdown-toggle hide-arrow g-0" style="font-size: 1.7rem;" href="javascript:void(0);"
                        data-bs-toggle="dropdown">
                        <i class='bx bxs-user-circle text-primary user-icon' style="font-size: 2rem;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ Auth::user()->email }}</h6>
                                        <small class="text-muted">{{ Auth::user()->userRole->role_name }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/profile/edit') }}">
                                <i class="bx bx-user bx-md me-3"></i><span>Profile</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/ubah-sandi') }}">
                                <i class="bx bx-lock-open-alt bx-md me-3"></i><span>Ubah Kata Sandi</span>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider my-1"></div>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <li>
                            <a class="dropdown-item" href="#" id="logout-btn">
                                <i class="bx bx-power-off bx-md me-3"></i><span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
        <!--/ User -->
    </ul>

    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-6">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Pengajuan Proposal Kegiatan</h5>
                </div>
                <form id="submission-form"
                    action="{{ $mode === 'new' ? route('submission-proposal.store') : route('submission-proposal.revise', $submission->submission_id) }}"
                    onsubmit="return handleFormSubmit(event)" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="doc_type_id">Jenis Dokumen</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="doc_type_id_view" value="Proposal Kegiatan"
                                    disabled />
                                <input type="hidden" name="doc_type_id" value="DT01" />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="event_name">Nama Kegiatan</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="event_name" id="event_name"
                                    placeholder="Masukkan nama Program Kerja"
                                    value="{{ $mode === 'revision' ? $submission->event_name : '' }}" required />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="organization">Penyelenggara</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="organization" id="organization"
                                    placeholder="Masukkan nama Organisasi"
                                    value="{{ $mode === 'revision' ? $submission->organization : '' }}" required />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="start_date">Tanggal Pelaksanaan</label>
                            <div class="col-sm-10">
                                <input type="date" class="form-control" name="start_date" id="start_date"
                                    value="{{ $mode === 'revision' ? $submission->start_date : '' }}" required
                                    style="cursor: pointer;" />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="end_date">Tanggal Berakhir</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        value="{{ $mode === 'revision' ? $submission->end_date : '' }}" required />
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="location">Lokasi Pelaksanaan</label>
                            <div class="col-sm-10">
                                <div class="input-group input-group-merge">
                                    <input type="text" name="location" id="location" class="form-control"
                                        placeholder="Masukkan lokasi pelaksanaan"
                                        value="{{ $mode === 'revision' ? $submission->location : '' }}" required />
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="project_leader">Ketua Pelaksana</label>
                            <div class="col-sm-10">
                                <input type="text" name="project_leader" id="project_leader"
                                    class="form-control phone-mask" placeholder="Masukkan nama Ketua Pelaksana"
                                    value="{{ $mode === 'revision' ? $submission->project_leader : '' }}" required />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="proposal_document">Dokumen Proposal</label>
                            <div class="col-sm-10">
                                <input type="file" name="proposal_document" id="proposal_document"
                                    class="form-control phone-mask" accept=".doc,.docx,.pdf"
                                    {{ $mode === 'revision' ? '' : 'required' }} required />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="letter_document">Surat Pengantar</label>
                            <div class="col-sm-10">
                                <input type="file" name="letter_document" id="letter_document"
                                    class="form-control phone-mask" placeholder="Masukkan nama Ketua Pelaksana"
                                    aria-label="658 799 8941" aria-describedby="basic-default-phone" required />
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-primary" id="submit">Ajukan Dokumen</button>
                            </div>
                        </div>
                    </div>
                </form>
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
                    window.location.href = "{{ route('document-tracking.index') }}";
                }
            });
        });
    </script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('logout-btn').addEventListener('click', function(event) {
            event.preventDefault(); // Mencegah submit form langsung

            Swal.fire({
                title: "Yakin ingin mengakhiri sesi?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#5DC264",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, logout!",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form logout jika dikonfirmasi
                    document.getElementById('logout-form').submit();
                }
            });
        });
    });
</script>
