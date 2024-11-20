@extends('layouts/contentNavbarLayout')

@section('title', 'Manajemen Akun')


<style>
    .swal2-container {
        z-index: 10000 !important;
    }
</style>

@section('content')

    <!-- Hoverable Table rows -->
    <div class="card">

        <div class="nav-item d-flex justify-content-end align-items-right m-4 me-12">
            <button type="button" class="btn btn-xs btn-info" data-bs-toggle="modal"
                data-bs-target="#createUserModal"href="{{ route('regulation-management.store') }}">
                <i class="bx bx-plus"></i>
            </button>
        </div>

        <div class="table-responsive text-nowrap">
            <table id="myTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>Email Akun</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Fakultas</th>
                        <th>No. Handphone</th>
                        <th class="text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if ($users->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada akun yang terdaftar</td>
                        </tr>
                    @else
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->userRole->role_name }}</td>
                                <td>{{ $user->faculty->faculty_name ?? '-' }}</td>
                                <td>{{ $user->phone_number ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#largeModal"
                                                onclick="openEditModal({{ $user->id }}, '{{ $user->email }}', '{{ $user->name }}', '{{ $user->role_id }}', '{{ $user->faculty_id ?? '' }}')">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </a>
                                            <form id="delete-form-{{ $user->id }}"
                                                action="{{ route('user-management.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a class="dropdown-item" type="button"
                                                    onclick="deleteSwal('{{ $user->id }}')">
                                                    <i class="bx bx-trash me-1"></i>
                                                    Delete
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal untuk Edit Akun --}}
    <div class="modal fade" id="largeModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <form id="editAccountForm" method="POST" action="{{ route('user-management.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Edit Akun</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-6">
                                <label for="name" class="form-label">Email</label>
                                <input class="form-control" type="text" id="email" name="email"
                                    value="{{ $user->email }}" disabled />
                            </div>
                            <div class="col mb-6">
                                <label for="name" class="form-label">Name Organisasi</label>
                                <input class="form-control" type="text" id="name" name="name" autofocus
                                    required />
                            </div>
                        </div>
                        <div class="row g-6">
                            <div class="col mb-6">
                                <label class="form-label" for="role_id">Role</label>
                                <select id="role_id" name="role_id" class="select2 form-select">
                                    <option value="{{ $user->userRole->role_name }}"></option>
                                    @foreach ($roles as $role_id => $role_name)
                                        <option value="{{ $role_id }}">
                                            {{ $role_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-6">
                            <div class="col mb-0">
                                <label class="form-label" for="faculty_id">Fakultas</label>
                                <select id="faculty_id" name="faculty_id" class="select2 form-select">
                                    <option value="{{ $user->faculty->faculty_name ?? 'Pilih Fakultas' }} "></option>
                                    @foreach ($faculties as $faculty_id => $faculty_name)
                                        <option value="{{ $faculty_id }}">
                                            {{ $faculty_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary me-2" onclick="successEditSwal()">Save
                            changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function openEditModal(id, email, name, roleId, facultyId) {
            // Mengisi field di modal
            document.getElementById('email').value = email;
            document.getElementById('name').value = name;
            document.getElementById('role_id').value = roleId;
            document.getElementById('faculty_id').value = facultyId;

            // Mengganti URL action pada form dengan ID user yang sesuai
            const form = document.getElementById('editAccountForm');
            form.action = `/manajemen-akun/${id}`; // Mengisi ID ke dalam URL
        }
    </script>

    <script type="text/javascript">
        function deleteSwal(id) {
            console.log(id);
            Swal.fire({
                title: "Hapus Akun",
                text: "Apakah Anda yakin akan menghapus akun ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#5DC264",
                cancelButtonColor: "#F47D20",
                confirmButtonText: "Hapus"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if user confirms deletion
                    document.getElementById(`delete-form-${id}`).submit();
                    Swal.fire({
                        title: "Sukses!",
                        text: "Akun berhasil dihapus",
                        icon: "success"
                    });
                }
            });
        }
    </script>

    {{-- Modal untuk Tambah Akun --}}

    {{-- <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <form action="{{ route('user-management.store') }}" method="POST">
            @csrf

            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Tambah Akun</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-6">
                            <label for="addEmail" class="col-md-2 col-form-label">Email</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="addEmail" name="addEmail"
                                    placeholder="Masukkan email organisasi atau lembaga" required />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="addName">Nama</label>
                            <div class="col-sm-10">
                                <input id="addName" name="addName" class="form-control"
                                    placeholder="Masukkan nama organisasi atau lembaga" required>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="form-label" for="addUserType">Kategori</label>
                            <select id="addUserType" name="addUserType" class="select2 form-select">
                                <option value="">Pilih Kategori</option>
                                @foreach ($types as $user_type_id => $type_description)
                                    <option value="{{ $user_type_id }}">
                                        {{ $type_description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row mb-6">
                            <label class="form-label" for="addRole">Role</label>
                            <select id="addRole" name="addRole" class="select2 form-select">
                                <option value="">Pilih Role</option>
                                @foreach ($roles as $role_id => $role_name)
                                    <option value="{{ $role_id }}">
                                        {{ $role_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-6">
                            <div class="col mb-0">
                                <label class="form-label" for="addFaculty">Fakultas</label>
                                <select id="addFaculty" name="addFaculty" class="select2 form-select">
                                    <option value="">Pilih Fakultas</option>
                                    @foreach ($faculties as $faculty_id => $faculty_name)
                                        <option value="{{ $faculty_id }}">
                                            {{ $faculty_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div> --}}

    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <form action="{{ route('user-management.store') }}" method="POST" onsubmit="return handleFormSubmit(event)">
            @csrf

            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Tambah Akun</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-6">
                            <label for="email" class="col-md-2 col-form-label">Email</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" id="email" name="email"
                                    placeholder="Masukkan email organisasi atau lembaga" required />
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="name">Nama</label>
                            <div class="col-sm-10">
                                <input id="name" name="name" class="form-control"
                                    placeholder="Masukkan nama organisasi atau lembaga" required>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="user_type_id">Kategori</label>
                            <div class="col-sm-10">
                                <select id="user_type_id" name="user_type_id" class="select2 form-select" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($types as $user_type_id => $type_description)
                                        <option value="{{ $user_type_id }}">
                                            {{ $type_description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="role_id">Role</label>
                            <div class="col-sm-10">
                                <select id="role_id" name="role_id" class="select2 form-select" required>
                                    <option value="">Pilih Role</option>
                                    @foreach ($roles as $role_id => $role_name)
                                        <option value="{{ $role_id }}">
                                            {{ $role_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="faculty_id">Fakultas</label>
                            <div class="col-sm-10">
                                <select id="faculty_id" name="faculty_id" class="select2 form-select">
                                    <option value="">Pilih Fakultas</option>
                                    @foreach ($faculties as $faculty_id => $faculty_name)
                                        <option value="{{ $faculty_id }}">
                                            {{ $faculty_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="organization_id">Organisasi</label>
                            <div class="col-sm-10">
                                <select id="organization_id" name="organization_id" class="select2 form-select">
                                    <option value="">Pilih Organisasi</option>
                                    @foreach ($organizations as $organization_id => $org_name)
                                        <option value="{{ $organization_id }}">
                                            {{ $org_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <label class="col-sm-2 col-form-label" for="password"></label>
                            <div class="col-sm-10">
                                <label class="col-sm-2 col-form-label" for="password">Password sudah diatur secara default
                                    menjadi <b>AdOrma123</b>.
                                    <br>Silakan login untuk dan ubah kata sandi
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary me-2"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

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
                        window.location.href = "{{ route('user-management.index') }}";
                    }
                });
            });
        </script>
    @endif



    <div class="mt-2 pt-4">
        <nav aria-label="Page navigation">
            <div class="ms-2 d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
                <div class="align-items-center">
                    <p class="text-primary small text-muted">
                        {!! __('Showing') !!}
                        <span class="fw-semibold">{{ $users->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="fw-semibold">{{ $users->lastItem() }}</span>
                        {!! __('of') !!}
                        <span class="fw-semibold">{{ $users->total() }}</span>
                        {!! __('results') !!}
                    </p>
                </div>
                <ul class="pagination pagination-sm justify-content-between">
                    @if ($users->onFirstPage())
                        <li class="page-item disabled prev">
                            <a class="page-link" href="javascript:void(0);"><i
                                    class="tf-icon bx bx-chevrons-left bx-sm"></i></a>
                        </li>
                    @else
                        <li class="page-item prev">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}"><i
                                    class="tf-icon bx bx-chevrons-left bx-sm"></i></a>
                        </li>
                    @endif

                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0);">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    @if ($users->hasMorePages())
                        <li class="page-item next">
                            <a class="page-link" href="{{ $users->nextPageUrl() }}"><i
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
