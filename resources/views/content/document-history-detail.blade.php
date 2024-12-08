@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Riwayat Dokumen')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- Notifications -->
                <div class="card-body">
                    <h5 class="mb-1">Detail Riwayat Dokumen</h5>
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
                                <td class="text-nowrap text-heading col-35">{{ $document->submission_id }}</td>
                                <td class="text-nowrap text-heading col-65">{{ $document->doc_type_name }}</td>
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

                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-nowrap col-35" style="background-color: #d3d3d3; color: #000;">Status
                                    Dokumen
                                </th>
                                <th class="text-nowrap col-65" style="background-color: #d3d3d3; color: #000;">Notes
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-nowrap text-heading col-35">
                                    <span
                                        class="badge bg-label-success btn-sm me-1 mb-2">{{ $document->doc_status }}</span><br>
                                    @if ($sikPath)
                                        <a href="/storage/{{ $sikPath->file_path }}" type="button"
                                            class="btn btn-sm btn-primary me-2">SIK<i
                                                class='bx bxs-download ms-1 small'></i></a>
                                    @endif

                                    @if ($document->doc_type_name == 'Proposal Kegiatan')
                                        <a href="/storage/{{ $docPath->file_path }}" type="button"
                                            class="btn btn-sm btn-primary me-2">Proposal
                                            Kegiatan<i class='bx bxs-download ms-1 small'></i></a>
                                    @elseif ($document->doc_type_name == 'Laporan Pertanggungjawaban')
                                        <a href="/storage/{{ $docPath->file_path }}" type="button"
                                            class="btn btn-sm btn-primary me-2">LPJ<i
                                                class='bx bxs-download ms-1 small'></i></a>
                                    @endif

                                </td>
                                <td class="text-nowrap text-heading col-65">
                                    <div class="accordion" id="accordionExample">
                                        @foreach ($docApprove as $approve)
                                            <div class="card accordion-item active">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button type="button" class="accordion-button"
                                                        data-bs-toggle="collapse" data-bs-target="#accordionOne"
                                                        aria-expanded="true" aria-controls="accordionOne">
                                                        {{ $approve->approver }}
                                                    </button>
                                                </h2>

                                                <div id="accordionOne" class="accordion-collapse collapse show"
                                                    data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        {{ $approve->comments }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
