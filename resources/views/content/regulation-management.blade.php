@extends('layouts/contentNavbarLayout')

@section('title', 'Riwayat Dokumen')

<style>
    .swal2-container {
        z-index: 10000 !important;
    }
</style>

@section('content')
    <div class="card">
        <div class="nav-item d-flex justify-content-end align-items-right m-4 me-8">
            <button type="button" class="btn btn-xs btn-info" data-bs-toggle="modal" data-bs-target="#createRegModal"
                href="{{ route('regulation-management.store') }}"><i
                    class="bx
                            bx-plus"></i></button>
        </div>
        <div class="table-responsive text-nowrap">
            <table id="myTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>Nomor Regulasi</th>
                        <th class="col-judul">Judul Regulasi</th>
                        <th class="col-isi">Isi Regulasi</th>
                        <th>Tanggal Input</th>
                        <th>Tanggal Update</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($regulations->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data regulasi yang tersedia</td>
                        </tr>
                    @else
                        @foreach ($regulations as $regulation)
                            <tr>
                                <td>{{ $regulation->regulation_id }}</td>
                                <td>{{ $regulation->announcement_title }}</td>
                                <td>{{ $regulation->announcement_description }}</td>
                                <td>{{ \Carbon\Carbon::parse($regulation->created_at)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($regulation->updated_at)->format('d M Y') }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-success mb-2" data-bs-toggle="modal"
                                        data-bs-target="#largeModal"
                                        onclick="openEditModal('{{ $regulation->regulation_id }}','{{ $regulation->announcement_title }}', {{ json_encode($regulation->announcement_description) }})"
                                        href="{{ route('regulation-management.update', $regulation->regulation_id) }}">
                                        <i class="bx bx-edit-alt me-1"></i>
                                    </button>
                                    <form id="delete-form-{{ $regulation->regulation_id }}"
                                        action="{{ route('regulation-management.destroy', $regulation->regulation_id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="testSwal('{{ $regulation->regulation_id }}')"
                                            class="btn btn-xs btn-danger">
                                            <i class="bx bx-trash me-1"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal untuk Edit Regulasi --}}
    <div class="modal fade" id="largeModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <form id="formEdit"
            action="{{ isset($regulation) ? route('regulation-management.update', $regulation->regulation_id) : '#' }}"
            method="POST">
            @csrf
            @if (@isset($regulation))
                @method('PUT')
            @endif

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Edit Regulasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-6">
                            <label for="announcement_title" class="col-md-2 col-form-label">Judul Regulasi</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="announcement_title" name="announcement_title"
                                    value="{{ old('announcement_title', $regulation->announcement_title ?? '') }}"
                                    required />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="announcement_description">Isi Regulasi</label>
                            <div class="col-sm-10">
                                <textarea id="announcement_description" rows="16" name="announcement_description" class="form-control"
                                    aria-label="Masukkan isi regulasi" aria-describedby="basic-icon-default-message2" required>{{ $regulation->announcement_description ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Modal untuk Tambah Regulasi --}}
    <div class="modal fade" id="createRegModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <form action="{{ route('regulation-management.store') }}" method="POST" onsubmit="return handleFormSubmit(event)">
            @csrf
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Tambah Regulasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-6">
                            <label for="announcement_title" class="col-md-2 col-form-label">Judul Regulasi</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="announcement_title"
                                    name="announcement_title" placeholder="Masukkan judul regulasi" required />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="announcement_description">Isi Regulasi</label>
                            <div class="col-sm-10">
                                <textarea id="announcement_description" rows="8" name="announcement_description" class="form-control"
                                    placeholder="Masukkan isi dari regulasi" aria-label="Masukkan isi regulasi"
                                    aria-describedby="basic-icon-default-message2" required></textarea>
                            </div>
                        </div>
                        {{-- <div class="row mb-6">
                            <label for="reg_file" class="col-md-2 col-form-label">File Regulasi</label>
                            <div class="col-md-10">
                                <input class="form-control" type="file" id="announcement_title"
                                    name="announcement_title"
                                    value="{{ old('announcement_title', $regulation->announcement_title ?? '') }}"
                                    required accept=".pdf" />
                            </div>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary me-2"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="mt-2 pt-4">
        <nav aria-label="Page navigation">
            <div class="ms-2 d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
                <div class="align-items-center">
                    <p class="text-primary small text-muted">
                        {!! __('Showing') !!}
                        <span class="fw-semibold">{{ $regulations->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="fw-semibold">{{ $regulations->lastItem() }}</span>
                        {!! __('of') !!}
                        <span class="fw-semibold">{{ $regulations->total() }}</span>
                        {!! __('results') !!}
                    </p>
                </div>
                <ul class="pagination pagination-sm justify-content-between">
                    @if ($regulations->onFirstPage())
                        <li class="page-item disabled prev">
                            <a class="page-link" href="javascript:void(0);"><i
                                    class="tf-icon bx bx-chevrons-left bx-sm"></i></a>
                        </li>
                    @else
                        <li class="page-item prev">
                            <a class="page-link" href="{{ $regulations->previousPageUrl() }}"><i
                                    class="tf-icon bx bx-chevrons-left bx-sm"></i></a>
                        </li>
                    @endif

                    @foreach ($regulations->getUrlRange(1, $regulations->lastPage()) as $page => $url)
                        @if ($page == $regulations->currentPage())
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0);">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    @if ($regulations->hasMorePages())
                        <li class="page-item next">
                            <a class="page-link" href="{{ $regulations->nextPageUrl() }}"><i
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

    <script type="text/javascript">
        function successSwal() {
            Swal.fire({
                position: "center",
                icon: "success",
                title: "Regulasi baru berhasil ditambahkan",
                showConfirmButton: false,
                timer: 1500
            });
        }

        function handleFormSubmit(event) {
            // Check if form is valid before showing success message
            if (event.target.checkValidity()) {
                event.preventDefault(); // Prevent the default form submission
                successSwal(); // Show the success alert
                event.target.submit(); // Submit the form after showing the alert
            }
        }

        function openEditModal(id, title, description) {
            // Mengisi field di modal
            document.getElementById('announcement_title').value = title;
            document.getElementById('announcement_description').value = description;

            // Mengganti URL action pada form dengan ID user yang sesuai
            const form = document.getElementById('formEdit');
            form.action = `/manajemen-regulasi/${id}`; // Mengisi ID ke dalam URL
        }
    </script>

    <script type="text/javascript">
        function testSwal(id) {
            console.log(id);
            Swal.fire({
                title: "Hapus Regulasi",
                text: "Apakah Anda yakin akan menghapus regulasi ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#B31010",
                confirmButtonText: "Ya, hapus",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if user confirms deletion
                    document.getElementById(`delete-form-${id}`).submit();
                    Swal.fire({
                        title: "Berhasil",
                        text: "Regulasi berhasil dihapus",
                        icon: "success"
                    });
                }
            });
        }
    </script>
@endsection
