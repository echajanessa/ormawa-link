@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
    @vite(['resources/assets/js/pages-account-settings-account.js'])
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-6">
                <!-- Account -->
                <div class="card-body">
                    <h5>Detail Dokumen</h5>
                </div>
                <div class="card-body pt-1">
                    <div class="row g-6">
                        <div class="col-md-6">
                            <label for="type" class="col-sm-2 col-form-label">Jenis Dokumen</label>
                            <input class="form-control" type="text" id="doc_type_name"
                                value="{{ $document->doc_type_name }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="submission_id" class="col-sm-2 col-form-label">Nomor Dokumen</label>
                            <input class="form-control" type="text" id="submission_id"
                                value="{{ $document->submission_id }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="event_name" class="col-sm-2 col-form-label">Nama Kegiatan</label>
                            <input class="form-control" type="text" id="event_name" value="{{ $document->event_name }}"
                                disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="project_leader" class="col-sm-2 col-form-label">Ketua Pelaksana</label>
                            <input type="text" class="form-control" id="project_leader"
                                value="{{ $document->project_leader }}" disabled />
                        </div>
                        <div class="col-md-6">
                            <label class="col-sm-2 col-form-label" for="organizer">Penyelenggara</label>
                            <div class="input-group input-group-merge">
                                <input type="text" id="organizer" class="form-control" value="{{ $document->organizer }}"
                                    disabled />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="created_at" class="col-sm-2 col-form-label">Tanggal Pengajuan Dokumen</label>
                            <input type="text" class="form-control" id="created_at" value="{{ $document->created_at }}"
                                disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="start_date" class="col-sm-2 col-form-label">Tanggal Pelaksanaan Kegiatan</label>
                            <input type="text" class="form-control" id="start_date" value="{{ $document->start_date }}"
                                disabled />
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="col-sm-2 col-form-label">Tanggal Berakhir Kegiatan</label>
                            <input type="text" class="form-control" id="end_date" value="{{ $document->end_date }}"
                                disabled />
                        </div>

                        <h6 class="pt-4">Dokumen</h6>

                        <div class="col-md-6">
                            @if ($proposalLpj)
                                <label for="start_date" class="col-sm-2 col-form-label pb-3">Proposal/Laporan
                                    Terbaru</label>
                                <div class="document-card">
                                    <div class="document-icon">
                                        <i class='bx bx-detail'></i>
                                    </div>
                                    <div class="document-info">
                                        <p>{{ $document->doc_type_name }}</p>
                                    </div>
                                    <a href="/storage/{{ $proposalLpj->file_path }}" target="_blank" class="btn btn-link">
                                        Preview<i class='bx bx-link-external ms-2'></i>
                                    </a>
                                </div>
                            @else
                                <p>Tidak ada proposal/LPJ yang diupload</p>
                            @endif
                        </div>

                        <div class="col-md-6">
                            @if ($suratPengantar)
                                <label for="start_date" class="col-sm-2 col-form-label pb-3">Surat Pengantar</label>
                                <div class="document-card">
                                    <div class="document-icon">
                                        <i class='bx bx-detail'></i>
                                    </div>
                                    <div class="document-info">
                                        <p>Surat Pengantar</p>
                                    </div>
                                    <a href="/storage/{{ $suratPengantar->file_path }}" target="_blank"
                                        class="btn btn-link">
                                        Preview<i class='bx bx-link-external ms-2'></i>
                                    </a>
                                </div>
                            @else
                                <p>Tidak ada surat pengantar yang diupload</p>
                            @endif
                        </div>

                        {{-- <form action="{{ route('document-approval.process', $document->submission_id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                        </form> --}}

                        <form id="approvalForm" action="{{ route('document-approval.process', $document->submission_id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-6">
                                <label for="decision" class="mb-2">Keputusan</label>
                                <select class="form-control" name="decision" id="decision" required>
                                    <option value="">Pilih Keputusan</option>
                                    <option value="approve">Setujui</option>
                                    <option value="reject">Tolak</option>
                                </select>
                            </div>
                            <div class="form-group mb-6">
                                <label for="comments" class="mb-2">Komentar</label>
                                <textarea class="form-control" name="comments" id="comments" rows="4" required></textarea>
                            </div>

                            <div class="form-group mb-6">
                                <label for="approval_document" class="mb-2">Unggah Dokumen Persetujuan</label>
                                <input type="file" class="form-control" name="approval_document"
                                    id="approval_document" required>
                            </div>

                            <button type="submit" id="submitButton" class="btn btn-success">Submit</button>
                        </form>

                    </div>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger"
            },
            buttonsStyling: false
        });

        const approvalForm = document.getElementById("approvalForm");
        if (approvalForm) {
            approvalForm.addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent form submission until after Swal confirmation

                swalWithBootstrapButtons.fire({
                    title: "Konfirmasi Tindakan",
                    text: "Apakah Anda yakin dengan keputusan Anda?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, submit",
                    cancelButtonText: "Tidak, batalkan",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show success Swal first
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Dokumen pengajuan berhasil disetujui",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            // After success message, submit the form
                            approvalForm.submit();
                            setTimeout(function() {
                                // Redirect to document approval page
                                window.location.href =
                                    "{{ route('document-approval.index') }}"; // Adjust this URL to your route
                            }, 1000);
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Batal",
                            text: "Tindakan Anda dibatalkan",
                            icon: "error"
                        });
                    }
                });
            });
        }
    });
</script>
@if (session('success'))
    <script>
        Swal.fire({
            title: "Success!",
            text: "{{ session('success') }}",
            icon: "success",
            confirmButtonText: "OK"
        });
    </script>
@endif
