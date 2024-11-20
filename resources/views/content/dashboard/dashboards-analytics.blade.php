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

@section('content')
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
                                    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body pb-6">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-nowrap col-35">Pengumuman Regulasi Dokumen
                                                            </th>
                                                            <th class="text-nowrap col-65">
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($regulations as $regulation)
                                                            <tr>
                                                                <td class="text-nowrap text-heading col-35">
                                                                    {{ $regulation->announcement_title }}</td>
                                                                <td class="text-nowrap text-heading col-65">
                                                                    {{ $regulation->announcement_description }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
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
        <div class="col-xxl-1 text-center">
            <p type="text" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="right" data-bs-html="true"
                title="<i class='bx bx-trending-up bx-xs' ></i> <span>Ringkasan Dokumen</span>">
                <b>Overview</b>
            </p>
        </div>
        <div class="col-xxl-11">

        </div>
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

        <!-- Total Revenue -->
        <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
            <div class="card">
                <div class="row row-bordered g-0">
                    <div class="col-lg-8">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Total Dokumen</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="totalRevenue" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded bx-lg text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalRevenue">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div id="totalRevenueChart" class="px-3"></div>
                    </div>
                    <div class="col-lg-4 d-flex align-items-center">
                        <div class="card-body px-xl-9">
                            <div class="text-center mb-6">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-primary">
                                        <script>
                                            document.write(new Date().getFullYear() - 1)
                                        </script>
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0);">2021</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">2020</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">2019</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div id="growthChart"></div>
                            <div class="text-center fw-medium my-6">62% Company Growth</div>

                            <div class="d-flex gap-3 justify-content-between">
                                <div class="d-flex">
                                    <div class="avatar me-2">
                                        <span class="avatar-initial rounded-2 bg-label-primary"><i
                                                class="bx bx-dollar bx-lg text-primary"></i></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>
                                            <script>
                                                document.write(new Date().getFullYear() - 1)
                                            </script>
                                        </small>
                                        <h6 class="mb-0">$32.5k</h6>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="avatar me-2">
                                        <span class="avatar-initial rounded-2 bg-label-info"><i
                                                class="bx bx-wallet bx-lg text-info"></i></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>
                                            <script>
                                                document.write(new Date().getFullYear() - 2)
                                            </script>
                                        </small>
                                        <h6 class="mb-0">$41.2k</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Total Revenue -->
        <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
            <div class="row">
                <div class="col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/paypal.png') }}" alt="paypal"
                                        class="rounded">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">Payments</p>
                            <h4 class="card-title mb-3">$2,456</h4>
                            <small class="text-danger fw-medium"><i class='bx bx-down-arrow-alt'></i> -14.82%</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between mb-4">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ asset('assets/img/icons/unicons/cc-primary.png') }}" alt="Credit Card"
                                        class="rounded">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-1">Transactions</p>
                            <h4 class="card-title mb-3">$14,857</h4>
                            <small class="text-success fw-medium"><i class='bx bx-up-arrow-alt'></i> +28.14%</small>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center flex-sm-row flex-column gap-10">
                                <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                    <div class="card-title mb-6">
                                        <h5 class="text-nowrap mb-1">Profile Report</h5>
                                        <span class="badge bg-label-warning">YEAR 2022</span>
                                    </div>
                                    <div class="mt-sm-auto">
                                        <span class="text-success text-nowrap fw-medium"><i
                                                class='bx bx-up-arrow-alt'></i> 68.2%</span>
                                        <h4 class="mb-0">$84,686k</h4>
                                    </div>
                                </div>
                                <div id="profileReportChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Order Statistics -->
        <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="mb-1 me-2">Statistik Dokumen </h5>
                        <p class="card-subtitle">{{ $totalDocuments }} Total Dokumen</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-6">
                        <div class="d-flex flex-column align-items-center gap-1">
                            <h3 class="mb-1">8,258</h3>
                            <small>Total Dokumen</small>
                        </div>
                        <div id="orderStatisticsChart"></div>
                    </div>
                    <ul class="p-0 m-0">
                        <li class="d-flex align-items-center mb-5">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary"><i class='bx bx-file'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Proposal Dokumen</h6>
                                </div>
                                <div class="user-progress">
                                    <h6 class="mb-0">{{ $proposalCount }}</h6>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center mb-5">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i class='bx bx-book'></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Laporan Pertanggungjawaban</h6>
                                </div>
                                <div class="user-progress">
                                    <h6 class="mb-0">{{ $lpjCount }}</h6>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Order Statistics -->

        <script>
            const proposalCount = {{ $proposalCount }};
            const lpjCount = {{ $lpjCount }};
            const chartOrderStatistics = document.querySelector('#orderStatisticsChart'),
                orderChartConfig = {
                    chart: {
                        height: 145,
                        width: 110,
                        type: 'donut'
                    },
                    labels: ['Proposal Kegiatan', 'Laporan Pertanggungjawaban'],
                    series: [proposalCount, lpjCount],
                    colors: [config.colors.success, config.colors.primary],
                    stroke: {
                        width: 5,
                        colors: [cardColor]
                    },
                    dataLabels: {
                        enabled: false,
                        formatter: function(val, opt) {
                            return parseInt(val) + '%';
                        }
                    },
                    legend: {
                        show: false
                    },
                    grid: {
                        padding: {
                            top: 0,
                            bottom: 0,
                            right: 15
                        }
                    },
                    states: {
                        hover: {
                            filter: {
                                type: 'none'
                            }
                        },
                        active: {
                            filter: {
                                type: 'none'
                            }
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '75%',
                                labels: {
                                    show: true,
                                    value: {
                                        fontSize: '18px',
                                        fontFamily: 'Public Sans',
                                        fontWeight: 500,
                                        color: headingColor,
                                        offsetY: -17,
                                        formatter: function(val) {
                                            return parseInt(val) + '%';
                                        }
                                    },
                                    name: {
                                        offsetY: 17,
                                        fontFamily: 'Public Sans'
                                    },
                                    total: {
                                        show: true,
                                        fontSize: '13px',
                                        color: legendColor,
                                        label: 'Weekly',
                                        formatter: function(w) {
                                            return '38%';
                                        }
                                    }
                                }
                            }
                        }
                    }
                };
            if (typeof chartOrderStatistics !== undefined && chartOrderStatistics !== null) {
                const statisticsChart = new ApexCharts(chartOrderStatistics, orderChartConfig);
                statisticsChart.render();
            }
        </script>

        <!-- Expense Overview -->
        <div class="col-md-6 col-lg-4 order-1 mb-6">
            <div class="card h-100">
                <div class="card-header nav-align-top">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-tabs-line-card-income" aria-controls="navs-tabs-line-card-income"
                                aria-selected="true">Income</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab">Expenses</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab">Profit</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                            <div class="d-flex mb-6">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="{{ asset('assets/img/icons/unicons/wallet.png') }}" alt="User">
                                </div>
                                <div>
                                    <p class="mb-0">Total Balance</p>
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">$459.10</h6>
                                        <small class="text-success fw-medium">
                                            <i class='bx bx-chevron-up bx-lg'></i>
                                            42.9%
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div id="incomeChart"></div>
                            <div class="d-flex align-items-center justify-content-center mt-6 gap-3">
                                <div class="flex-shrink-0">
                                    <div id="expensesOfWeek"></div>
                                </div>
                                <div>
                                    <h6 class="mb-0">Income this week</h6>
                                    <small>$39k less than last week</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Expense Overview -->
    </div>

    <!-- Hoverable Table rows -->
    <div class="card">
        <h5 class="card-header">Data Administrasi Dokumen Organisasi Mahasiswa</h5>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Kegiatan</th>
                        <th>Jenis Dokumen</th>
                        <th>Organisasi</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <tr>
                        <td><i class="bx bxl-angular bx-md text-danger me-4"></i> <span>Desa Binaan FTI Untar
                                2025</span></td>
                        <td>Proposal Kegiatan</td>
                        <td>
                            Test
                        </td>
                        <td><span class="badge bg-label-primary me-1">Ditolak</span></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);"><i
                                            class="bx bx-edit-alt me-1"></i>
                                        Edit</a>
                                    <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                                        Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    {{-- <tr>
                            <td><i class="bx bxl-vuejs bx-md text-success me-4"></i> <span>LK External 2025</span></td>
                            <td>BEM UNTAR</td>
                            <td>
                                <ul class="list-unstyled m-0 avatar-group d-flex align-items-center">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        class="avatar avatar-xs pull-up" title="Lilian Fuller">
                                        <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar"
                                            class="rounded-circle">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                                        <img src="{{ asset('assets/img/avatars/6.png') }}" alt="Avatar"
                                            class="rounded-circle">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        class="avatar avatar-xs pull-up" title="Christina Parker">
                                        <img src="{{ asset('assets/img/avatars/7.png') }}" alt="Avatar"
                                            class="rounded-circle">
                                    </li>
                                </ul>
                            </td>
                            <td><span class="badge bg-label-info me-1">Disetujui</span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i
                                                class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                                            Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><i class="bx bxl-bootstrap bx-md text-primary me-4"></i> <span>Bootstrap Project</span>
                            </td>
                            <td>Jerry Milton</td>
                            <td>
                                <ul class="list-unstyled m-0 avatar-group d-flex align-items-center">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        class="avatar avatar-xs pull-up" title="Lilian Fuller">
                                        <img src="{{ asset('assets/img/avatars/5.png') }}" alt="Avatar"
                                            class="rounded-circle">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                                        <img src="{{ asset('assets/img/avatars/6.png') }}" alt="Avatar"
                                            class="rounded-circle">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        class="avatar avatar-xs pull-up" title="Christina Parker">
                                        <img src="{{ asset('assets/img/avatars/7.png') }}" alt="Avatar"
                                            class="rounded-circle">
                                    </li>
                                </ul>
                            </td>
                            <td><span class="badge bg-label-warning me-1">Diproses</span></td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i
                                                class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i
                                                class="bx bx-trash me-1"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
    <!--/ Hoverable Table rows -->

    <hr class="my-12">



@endsection
