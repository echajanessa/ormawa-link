@extends('layouts/contentNavbarLayout')

@section('title', 'Riwayat Dokumen')

@section('content')
    <!-- Hoverable Table rows -->
    <div class="card">
        <h5 class="card-header">Riwayat Administrasi Dokumen Organisasi Mahasiswa</h5>
        <div class="table-responsive text-nowrap">
            <table id="myTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Jenis Dokumen</th>
                        <th>Status</th>
                        <th>Keterlambatan</th>
                        <th>Detail Dokumen</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($documents as $document)
                        <tr>
                            <td>{{ $document->event_name }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($document->start_date)->format('d M Y') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($document->end_date)->format('d M Y') }}</td>
                            <td>{{ $document->doc_type_name }}</td>
                            <td>
                                <span class="badge bg-label-success me-1">{{ $document->doc_status }}</span>
                            </td>
                            <td>
                                @php
                                    $keterlambatan = 0; // Inisialisasi default

                                    if ($document->doc_type_id == 'DT01') {
                                        // Hitung keterlambatan 45 hari sebelum start_date
                                        $daysDifference = now()->diffInDays($document->start_date, false);
                                        // Jika start_date kurang dari 45 hari dari sekarang, hitung keterlambatan
                                        if ($daysDifference < 45) {
                                            $keterlambatan = floor(45 - $daysDifference);
                                        }
                                    } elseif ($document->doc_type_id == 'DT05') {
                                        // Hitung keterlambatan 30 hari setelah end_date
                                        $daysAfterEnd = now()->diffInDays($document->end_date, false);
                                        // Jika end_date lebih dari 30 hari yang lalu, hitung keterlambatan
                                        if ($daysAfterEnd > 30) {
                                            $keterlambatan = floor($daysAfterEnd - 30);
                                        }
                                    }
                                @endphp
                                {{ $keterlambatan }} Hari
                            </td>
                            <td class="text-center">
                                <a href="{{ route('history-detail', $document->submission_id) }}"
                                    class="btn btn-xs btn-primary me-2"><i class='bx bx-detail small me-2'></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada dokumen yang diajukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-2 pt-4">
        <nav aria-label="Page navigation">
            <div class="ms-2 d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
                <div class="align-items-center">
                    <p class="text-primary small text-muted">
                        {!! __('Showing') !!}
                        <span class="fw-semibold">{{ $documents->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="fw-semibold">{{ $documents->lastItem() }}</span>
                        {!! __('of') !!}
                        <span class="fw-semibold">{{ $documents->total() }}</span>
                        {!! __('results') !!}
                    </p>
                </div>
                <ul class="pagination pagination-sm justify-content-between">
                    @if ($documents->onFirstPage())
                        <li class="page-item disabled prev">
                            <a class="page-link" href="javascript:void(0);"><i
                                    class="tf-icon bx bx-chevrons-left bx-sm"></i></a>
                        </li>
                    @else
                        <li class="page-item prev">
                            <a class="page-link" href="{{ $documents->previousPageUrl() }}"><i
                                    class="tf-icon bx bx-chevrons-left bx-sm"></i></a>
                        </li>
                    @endif

                    @foreach ($documents->getUrlRange(1, $documents->lastPage()) as $page => $url)
                        @if ($page == $documents->currentPage())
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0);">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    @if ($documents->hasMorePages())
                        <li class="page-item next">
                            <a class="page-link" href="{{ $documents->nextPageUrl() }}"><i
                                    class="tf-icon bx bx-chevrons-right bx-sm"></i></a>
                        </li>
                    @else
                        <li class="page-item disabled next">
                            <a class="page-link" href="javascript:void(0);"><i
                                    class="tf-icon bx bx-chevrons-right bx-sm"></i></a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
    <!--/ Hoverable Table rows -->
    <hr class="my-12">
@endsection
