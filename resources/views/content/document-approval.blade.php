@extends('layouts/contentNavbarLayout')

@section('title', 'Persetujuan Dokumen')
<div id="loadingData" class="demo-inline-spacing loadingData d-none">
    <div class="loading-overlay">
    </div>
    <div class="spinner-grow spinner-wrapper" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <div class="spinner-grow spinner-wrapper text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <div class="spinner-grow spinner-wrapper" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

@section('content')
    <!-- Hoverable Table rows -->
    <div class="card">
        <h5 class="card-header">Persetujuan Dokumen</h5>
        <div class="table-responsive text-nowrap">
            <table id="myTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Penyelenggara</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Jenis Dokumen</th>
                        <th>Keterlambatan</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($documentSubmissions as $document)
                        <tr>
                            <td>{{ $document->submission_id }}</td>
                            <td>{{ $document->name }}</td>
                            <td>{{ $document->event_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($document->start_date)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($document->end_date)->format('d M Y') }}</td>
                            <td>{{ $document->type }}</td>
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
                                <a href="{{ route('document-approval.show', $document->submission_id) }}"
                                    class="btn btn-xs btn-primary" onclick="showLoadingProg(true);">
                                    Detail
                                </a>
                            </td>
                    @endforeach
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
                        <span class="fw-semibold">{{ $documentSubmissions->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="fw-semibold">{{ $documentSubmissions->lastItem() }}</span>
                        {!! __('of') !!}
                        <span class="fw-semibold">{{ $documentSubmissions->total() }}</span>
                        {!! __('results') !!}
                    </p>
                </div>
                <ul class="pagination pagination-sm justify-content-between">
                    @if ($documentSubmissions->onFirstPage())
                        <li class="page-item disabled prev">
                            <a class="page-link" href="javascript:void(0);"><i
                                    class="tf-icon bx bx-chevrons-left bx-sm"></i></a>
                        </li>
                    @else
                        <li class="page-item prev">
                            <a class="page-link" href="{{ $documentSubmissions->previousPageUrl() }}"><i
                                    class="tf-icon bx bx-chevrons-left bx-sm"></i></a>
                        </li>
                    @endif

                    @foreach ($documentSubmissions->getUrlRange(1, $documentSubmissions->lastPage()) as $page => $url)
                        @if ($page == $documentSubmissions->currentPage())
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0);">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    @if ($documentSubmissions->hasMorePages())
                        <li class="page-item next">
                            <a class="page-link" href="{{ $documentSubmissions->nextPageUrl() }}"><i
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menangani tombol Detail
        window.showDetail = function(event, url) {
            event.preventDefault(); // Mencegah navigasi langsung
            showLoadingProg(true); // Tampilkan loading
            setTimeout(function() {
                // Beri sedikit jeda agar loading terlihat sebelum navigasi
                window.location.href = url; // Navigasi ke URL
            }, 100);
        };

        // Fungsi untuk menampilkan atau menyembunyikan loading
        function showLoadingProg(show) {
            const loadingElement = document.getElementById("loadingData");
            if (show) {
                loadingElement.classList.remove("d-none"); // Tampilkan loading
            } else {
                loadingElement.classList.add("d-none"); // Sembunyikan loading
            }
        }
    });
</script>
