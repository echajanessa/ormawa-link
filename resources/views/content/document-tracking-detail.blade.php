@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Dokumen')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- Notifications -->
                <div class="card-body">
                    <h5 class="mb-1">Detail</h5>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-nowrap col-35" style="background-color: #d3d3d3; color: #000;">Nomor Dokumen
                                </th>
                                <th class="text-nowrap col-65" style="background-color: #d3d3d3; color: #000;">Jenis Dokumen
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-nowrap text-heading">{{ $document->submission_id }}</td>
                                <td class="text-nowrap text-heading">{{ $document->doc_type_name }}</td>
                                {{-- <td>
                                    <div class="form-check mb-0 d-flex justify-content-center align-items-center">
                                        <input class="form-check-input" type="checkbox" id="defaultCheck1" checked />
                                    </div>
                                </td> --}}
                            </tr>
                        </tbody>
                    </table>

                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-nowrap col-35" style="background-color: #d3d3d3; color: #000;">Detail
                                    Kegiatan
                                </th>
                                <th class="text-nowrap col-65" style="background-color: #d3d3d3; color: #000;">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-nowrap text-heading col-35">Nama Kegiatan</td>
                                <td class="text-nowrap text-heading col-65">{{ $document->event_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-nowrap text-heading col-35">Tanggal Mulai</td>
                                <td class="text-nowrap text-heading col-65">
                                    {{ \Carbon\Carbon::parse($document->start_date)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-nowrap text-heading col-35">Tanggal Berakhir</td>
                                <td class="text-nowrap text-heading col-65">
                                    {{ \Carbon\Carbon::parse($document->end_date)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-nowrap text-heading col-35">Lokasi Pelaksanaan</td>
                                <td class="text-nowrap text-heading col-65">{{ $document->location }}</td>
                            </tr>
                            <tr>
                                <td class="text-nowrap text-heading col-35">Ketua Pelaksana</td>
                                <td class="text-nowrap text-heading col-65">{{ $document->project_leader }}</td>
                            </tr>
                            <tr>
                                <td class="text-nowrap text-heading col-35">Penyelenggara</td>
                                <td class="text-nowrap text-heading col-65">{{ $document->organization }}</td>
                            </tr>
                            <tr>
                                <td class="text-nowrap text-heading col-35">Fakultas</td>
                                <td class="text-nowrap text-heading col-65">{{ $organizer->faculty_name ?? '-' }}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="my-6">

            <div class="row">
                @foreach ($approvers as $index => $approver)
                    <div class="col-xl-6 mb-6 order-0">
                        <div class="card">
                            <div class="d-flex align-items-center row">
                                <div class="col-sm-7">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary mb-2">Approver {{ $index + 1 }}</h5>
                                        <h4 class="mb-0">
                                            {{ $approver['name'] }} <!-- Nama Approver -->
                                        </h4>
                                        <p class="mb-4">
                                            {{ $approver['email'] ?? 'Email tidak tersedia' }} <!-- Email Approver -->
                                        </p>

                                        @if ($approver['is_rejected'])
                                            @if ($document->doc_type_id == 'DT01')
                                                {{-- Proposal Kegiatan --}}
                                                <a data-toggle="modal" class="btn btn-warning"
                                                    href="{{ route('submission-proposal.edit', $document->submission_id) }}">
                                                    Upload ulang Proposal Kegiatan
                                                </a>
                                            @elseif ($document->doc_type_id == 'DT05')
                                                {{-- Laporan Pertanggungjawaban --}}
                                                <a data-toggle="modal" class="btn btn-warning"
                                                    href="{{ route('submission-lpj.edit', $document->submission_id) }}">
                                                    Upload ulang LPJ
                                                </a>
                                            @endif
                                        @else
                                            @if ($approver['file_path'])
                                                <a href="{{ asset('storage/' . $approver['file_path']) }}"
                                                    class="btn btn-primary" target="_blank">
                                                    Download Signed Document
                                                </a>
                                            @else
                                                <span class="badge bg-label-secondary">Dokumen belum disetujui</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-5 d-flex justify-content-center align-items-center text-sm-left">
                                    <div class="card-body">
                                        @php
                                            $badgeClass = '';
                                            switch ($document->doc_status) {
                                                case 'Ditinjau BEM':
                                                case 'Ditinjau DPM':
                                                case 'Ditinjau Binma':
                                                case 'Ditinjau Dekan':
                                                case 'Ditinjau Lemawa':
                                                    $badgeClass = 'bg-label-info';
                                                    break;
                                                case 'Revisi BEM':
                                                case 'Revisi DPM':
                                                case 'Revisi Binma':
                                                case 'Revisi Dekan':
                                                case 'Revisi Lemawa':
                                                    $badgeClass = 'bg-label-warning';
                                                    break;
                                                case 'Reject BEM':
                                                case 'Reject DPM':
                                                case 'Reject Binma':
                                                case 'Reject Dekan':
                                                case 'Reject Lemawa':
                                                    $badgeClass = 'bg-label-danger';
                                                    break;
                                                case 'Approved':
                                                    $badgeClass = 'bg-label-success';
                                                    break;
                                                default:
                                                    $badgeClass = 'bg-label-secondary';
                                                    break;
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }} me-1 p-3">{{ $approver['status'] }}</span>
                                        <!-- Status Approver -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <style>
            table {
                width: 100%;
                /* Atur lebar tabel 100% dari kontainer */
                border-collapse: collapse;
                /* Menghilangkan jarak antara border sel */
            }

            th,
            td {
                padding: 10px;
                /* Atur padding sel */
            }

            th {
                background-color: #f2f2f2;
                /* Warna latar belakang judul tabel */
            }

            .col-35 {
                width: 35%;
                /* Lebar kolom kiri */
            }

            .col-65 {
                width: 65%;
                /* Lebar kolom kanan */
            }

            /* Pastikan kolom tidak dapat dirubah ukurannya */
            table {
                table-layout: fixed;
                /* Atur layout tabel agar kolom memiliki ukuran tetap */
            }
        </style>
    </div>
@endsection
