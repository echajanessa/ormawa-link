@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
    $containerNav = $containerNav ?? 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';
@endphp

<style>
    .swal2-container {
        z-index: 10000 !important;
    }
</style>

<style>
    .user-icon {
        font-size: 1.9rem !important;
    }
</style>

<!-- Navbar -->
@if (isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    <nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme"
        id="layout-navbar">
@endif
@if (isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="{{ $containerNav }}">
@endif

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">@include('_partials.macros', ['width' => 25, 'withbg' => 'var(--bs-primary)'])</span>
            <span
                class="app-brand-text demo menu-text fw-bold text-heading">{{ config('variables.templateName') }}</span>
        </a>
    </div>
    <div id="searchResults"></div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
    <div
        class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="bx bx-menu bx-md"></i>
        </a>
    </div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <!-- Search -->
    <div class="navbar-nav align-items-center">
        <div class="nav-item d-flex align-items-center">
            <i class="bx bx-search bx-md"></i>
            <input type="text" id="searchInput" class="form-control border-0 shadow-none ps-1 ps-sm-2"
                placeholder="Search..." aria-label="Search...">
        </div>
    </div>
    <div id="searchResults"></div>
    <!-- /Search -->
    <ul class="navbar-nav flex-row align-items-center ms-auto">
        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow g-0 " style="font-size: 1.7rem;" href="javascript:void(0);"
                data-bs-toggle="dropdown">
                <i class='bx bxs-user-circle text-primary user-icon'></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="javascript:void(0);">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
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
        </li>
        <!--/ User -->
    </ul>
</div>

@if (!isset($navbarDetached))
    </div>
@endif
</nav>
<!-- / Navbar -->
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

<script>
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
</script>

<script>
    function showLoadingProg(show) {
        console.log(123)
        if (show) {
            document.getElementById("loadingData").style.display = "block";
        } else {
            document.getElementById("loadingData").style.display = "none";
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var searchTerm = this.value.toLowerCase();
            var rows = document.querySelectorAll('#myTable tbody tr');

            rows.forEach(function(row) {
                var cells = row.getElementsByTagName('td');
                var found = false;

                // Loop through each cell in the row
                for (var i = 0; i < cells.length; i++) {
                    if (cells[i].innerText.toLowerCase().includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }

                // Show or hide the row based on search term match
                if (found) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
{{-- 
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/persetujuan-dokumen/count') // URL sesuai route Anda
            .then(response => response.json())
            .then(data => {
                const approvalBadge = document.querySelector('.badge.bg-danger');
                if (approvalBadge && data.count !== undefined) {
                    approvalBadge.textContent = data.count;
                }
            })
            .catch(error => console.error('Error fetching pending approvals:', error));
    });
</script> --}}


{{-- <script>
    function searchData(query) {
        fetch(`/search?query=${query}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                const tableBody = document.querySelector(
                    "#table-body"); // Sesuaikan dengan ID atau class tabel Anda
                tableBody.innerHTML = ""; // Kosongkan isi tabel sebelum mengisi dengan data baru

                data.results.forEach(item => {
                    const row = document.createElement("tr");

                    // Tambahkan data ke dalam kolom sesuai struktur tabel Anda
                    row.innerHTML = `
                    <td>${item.submission_id}</td>
                    <td>${item.event_name}</td>
                    <td>${new Date(item.start_date).toLocaleDateString()}</td>
                    <td>${new Date(item.end_date).toLocaleDateString()}</td>
                    <td>${item.doc_type_id}</td>
                    <td>${item.status_id}</td>
                `;

                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error("Error:", error);
            });
    }

    // Panggil searchData ketika user mengetik di input
    document.querySelector("#search-input").addEventListener("keyup", function() {
        const query = this.value;
        searchData(query);
    });
</script> --}}
