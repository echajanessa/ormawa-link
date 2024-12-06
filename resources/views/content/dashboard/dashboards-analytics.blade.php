@php
    $isNavbar = false;
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
@endsection

<style>
    .swal2-container {
        z-index: 10000 !important;
    }
</style>

@section('content')
    <!-- /Search -->
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
        @if (in_array($userRole, ['RL009', 'RL008', 'RL007', 'RL006']))
            <div class="col-xxl-8 mb-6 order-0">
                <div class="card">
                    <div class="d-flex align-items-start row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                @if ($lastProposal)
                                    @if ($daysSinceEnd >= 7 && $daysRemaining > 0)
                                        <h5 class="card-title text-primary mb-3">PERINGATAN PENGUMPULAN LPJ</h5>
                                        <p class="mb-6">
                                            Kegiatan <b>{{ $lastProposal->event_name }}</b> kamu telah selesai <b
                                                class="text-primary">{{ $daysSinceEnd }}
                                                hari </b>
                                            yang lalu. Saatnya unggah LPJ
                                            kegiatan sebelum melewati
                                            batas waktu pengumpulan.
                                        </p>
                                        <a href="{{ url('/pengajuan-lpj') }}" class="btn btn-sm btn-outline-primary">Ajukan
                                            Dokumen</a>
                                    @elseif ($daysRemaining <= 0)
                                        <h5 class="card-title text-primary mb-3">PERINGATAN PENGUMPULAN LPJ</h5>
                                        <p class="text-danger mb-6">
                                            Batas waktu pengumpulan LPJ telah terlewati. Segera hubungi pihak terkait jika
                                            membutuhkan bantuan.
                                        </p>
                                    @else
                                        <h5 class="card-title text-primary mb-3">Halo, {{ $user->name }}! 👋</h5>
                                        <p class="mb-6">
                                            Jangan lupa untuk kumpulkan Proposal Kegiatan kamu 45 hari sebelum kegiatan
                                            dilaksanakan
                                            dan kumpulkan LPJ kamu maksimal 30 hari setelah kegiatan berakhir, ya!
                                        </p>
                                    @endif
                                @else
                                    <h5 class="card-title text-primary mb-3">Halo, {{ $user->name }}! 👋</h5>
                                    <p class="mb-6">
                                        Jangan lupa untuk kumpulkan Proposal Kegiatan kamu 45 hari sebelum kegiatan
                                        dilaksanakan
                                        dan kumpulkan LPJ kamu maksimal 30 hari setelah kegiatan berakhir, ya!
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-6">
                                <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="175"
                                    class="scaleX-n1-rtl" alt="View Badge User">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-4 mb-6 order-0">
                <div class="card">
                    <div class="d-flex align-items-start row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">REGULASI DOKUMEN</h5>
                                <p class="mb-6">
                                    Sebelum melakukan pengumpulan dokumen, jangan lupa untuk cek regulasi terlebih
                                    dahulu.
                                </p>

                                <a href="javascript:;" class="btn btn-sm btn-outline-primary mb-4" data-bs-toggle="modal"
                                    data-bs-target="#exLargeModal">Cek Regulasi</a>
                                <!-- Modal -->
                                <div class="modal fade" id="exLargeModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body pb-6">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-nowrap">Pengumuman Regulasi
                                                            </th>
                                                            <th>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($regulations as $regulation)
                                                            <tr>
                                                                <td>
                                                                    {{ $regulation->announcement_title }}</td>
                                                                <td>
                                                                    {{ $regulation->announcement_description }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-xxl-1 text-center ">
            <p type="text" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true"
                title="<i class='bx bx-trending-up bx-xs' ></i> <span>Ringkasan Dokumen</span>">
                <b>Overview</b>
            </p>
        </div>

        <form method="GET" action="{{ route('dashboard') }}">
            <div class="btn-group mb-6">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" id="selected-period">
                    Pilih Periode
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <h6 class="dropdown-header text-uppercase">Silakan Pilih Periode</h6>
                    </li>
                    @for ($year = 2020; $year <= now()->year; $year++)
                        <li>
                            <a class="dropdown-item period-item" href="{{ route('dashboard', ['year' => $year]) }}">
                                Periode Agustus {{ $year }} - Juli {{ $year + 1 }}
                            </a>
                        </li>
                    @endfor
                </ul>
            </div>

            {{-- <select name="year" id="year" class="form-control" onchange="this.form.submit()">
                @for ($i = 2020; $i <= date('Y'); $i++)
                    <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>
                        Periode Agustus {{ $i }} - Juli {{ $i + 1 }}
                    </option>
                @endfor
            </select> --}}
        </form>
        <div class="col-xxl-6 mb-6 order-0">
            <div class="card">
                <div class="align-items-center row g-0">
                    <div class="col-3 col-md-3 justify-content-center d-flex">
                        <img src="{{ asset('assets/img/illustrations/document-card-3.png') }}" style="width: 60px;"
                            style="height: 100px" class="scaleX-n1-rtl" alt="View Badge User">
                    </div>
                    <div class="col-sm-9">
                        <div class="card-body">
                            <h6 class="card-title align-items-center d-flex mb-4">Dokumen yang Diajukan <i
                                    class='bx bx-info-circle ms-2 text-primary' data-bs-toggle="tooltip"
                                    data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true"
                                    title="<i class='bx bx-file bx-xs' ></i> <span>Jumlah keseluruhan dari dokumen yang sudah diproses</span>"></i>
                            </h6>
                            <h4 class="mb-6 text-primary mb-6">
                                {{ $totalDocuments }} Dokumen
                            </h4>
                            <a href="{{ url('/riwayat-dokumen') }}" class="card-title align-items-center d-flex">Lihat
                                riwayat dokumen <i class='bx bx-chevron-right text-primary'></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-6 mb-6 order-0">
            <div class="card">
                <div class="align-items-center row g-0">
                    <div class="col-3 col-md-3 justify-content-center d-flex">
                        <img src="{{ asset('assets/img/illustrations/document-card.png') }}" style="width: 60px;"
                            style="height: 100px" class="scaleX-n1-rtl" alt="View Badge User">
                    </div>
                    <div class="col-sm-9">
                        <div class="card-body">
                            <h6 class="card-title align-items-center d-flex mb-4">Proposal Kegiatan <i
                                    class='bx bx-info-circle ms-2 text-primary' data-bs-toggle="tooltip"
                                    data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true"
                                    title="<i class='bx bx-file bx-xs' ></i> <span>Jumlah dokumen kategori Proposal Kegiatan</span>"></i>
                            </h6>
                            <h4 class="mb-6 text-primary mb-6">
                                {{ $proposalCount }} Dokumen
                            </h4>
                            <a href="{{ url('/riwayat-dokumen') }}" class="card-title align-items-center d-flex">Lihat
                                riwayat dokumen <i class='bx bx-chevron-right text-primary'></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-6 mb-6 order-0">
            <div class="card">
                <div class="align-items-center row g-0">
                    <div class="col-3 col-md-3 justify-content-center d-flex">
                        <img src="{{ asset('assets/img/illustrations/document-card-2.png') }}" style="width: 60px;"
                            style="height: 100px" class="scaleX-n1-rtl" alt="View Badge User">
                    </div>
                    <div class="col-sm-9">
                        <div class="card-body">
                            <h6 class="card-title align-items-center d-flex mb-4">Laporan Pertanggungjawaban<i
                                    class='bx bx-info-circle ms-2 text-primary' data-bs-toggle="tooltip"
                                    data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true"
                                    title="<i class='bx bx-file bx-xs' ></i> <span>Jumlah dokumen kategori Laporan Pertanggungjawaban</span>"></i>
                            </h6>
                            <h4 class="mb-6 text-primary mb-6">
                                {{ $lpjCount }} Dokumen
                            </h4>
                            <a href="{{ url('/riwayat-dokumen') }}" class="card-title align-items-center d-flex">Lihat
                                riwayat dokumen <i class='bx bx-chevron-right text-primary'></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-6 mb-6 order-0">
            <div class="card">
                <div class="align-items-center row g-0">
                    <div class="col-3 col-md-3 justify-content-center d-flex">
                        <img src="{{ asset('assets/img/illustrations/document-card-4.png') }}" style="width: 60px;"
                            style="height: 100px" class="scaleX-n1-rtl" alt="View Badge User">
                    </div>
                    <div class="col-sm-9">
                        <div class="card-body">
                            <h6 class="card-title align-items-center d-flex mb-4">Dokumen Aktif<i
                                    class='bx bx-info-circle ms-2 text-primary' data-bs-toggle="tooltip"
                                    data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true"
                                    title="<i class='bx bx-file bx-xs' ></i> <span>Jumlah dokumen yang masih dalam proses persetujuan</span>"></i>
                            </h6>
                            <h4 class="mb-6 text-primary mb-6">
                                {{ $activeDocuments }} Dokumen
                            </h4>
                            <a href="{{ url('/pemantauan-proses') }}" class="card-title align-items-center d-flex">Lihat
                                progres dokumen <i class='bx bx-chevron-right text-primary'></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (session('approval_reminder') && count(session('approval_reminder')) > 0)
            <div class="modal fade" id="approvalReminderModal" tabindex="-1" aria-labelledby="approvalReminderLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="approvalReminderLabel">Reminder Approval</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda memiliki dokumen yang perlu disetujui:</p>
                            <ul>
                                @foreach (session('approval_reminder') as $approval)
                                    <li>
                                        <strong>Nomor Dokumen:</strong> {{ $approval->submission_id }} <br>
                                        <strong>Status:</strong> {{ $approval->status_description }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <a href="{{ route('document-approval.index') }}" class="btn btn-primary">Lihat Dokumen</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <hr class="my-12">
    @endsection
    @section('scripts')
        @if (session('approval_reminder') && count(session('approval_reminder')) > 0)
            <script>
                console.log('Modal script executed');
                // Tampilkan modal otomatis
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = new bootstrap.Modal(document.getElementById('approvalReminderModal'));
                    modal.show();
                });
            </script>
        @endif
    @endsection

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectedPeriodButton = document.getElementById('selected-period');
            const periodItems = document.querySelectorAll('.period-item');

            // Cek apakah ada periode yang disimpan di localStorage
            const savedPeriod = localStorage.getItem('selectedPeriod');
            if (savedPeriod) {
                selectedPeriodButton.textContent = savedPeriod;
            }

            // Tambahkan event listener untuk setiap item
            periodItems.forEach(item => {
                item.addEventListener('click', function(event) {
                    event.preventDefault(); // Mencegah perpindahan halaman langsung

                    // Ambil teks dari item yang diklik
                    const selectedText = this.textContent;

                    // Simpan periode ke localStorage
                    localStorage.setItem('selectedPeriod', selectedText);

                    // Perbarui teks tombol
                    selectedPeriodButton.textContent = selectedText;

                    // Redirect ke URL
                    window.location.href = this.href;
                });
            });
        });
    </script>
