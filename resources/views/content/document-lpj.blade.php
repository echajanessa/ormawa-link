@extends('layouts/contentNavbarLayout')

@section('title', 'Laporan Pertanggungjawaban')

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-xxl">
            <div class="card mb-6">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Pengajuan Laporan Pertanggungjawaban</h5>
                </div>
                <form
                    action="{{ $mode === 'new' ? route('submission-proposal.store') : route('submission-lpj.revise', $submission->submission_id) }}"
                    onsubmit="return handleFormSubmit(event)" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <form>
                            <div class="row mb-6">
                                <label class="col-sm-2 col-form-label" for="doc_type_id">Jenis Dokumen</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="doc_type_id_view"
                                        value="Laporan Pertanggungjawaban" disabled />
                                    <input type="hidden" name="doc_type_id" value="DT05" />
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
                                        value="{{ $mode === 'revision' ? $submission->start_date : '' }}" required />
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
                                        class="form-control phone-mask" {{ $mode === 'revision' ? '' : 'required' }}
                                        required />
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
                        </form>
                    </div>
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
