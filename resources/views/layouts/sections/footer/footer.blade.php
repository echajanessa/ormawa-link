@php
    $containerFooter = !empty($containerNav) ? $containerNav : 'container-fluid';
@endphp

<!-- Basic footer -->
<section id="basic-footer">
    <footer class="footer bg-light">
        <div
            class="container-fluid d-flex flex-md-row flex-column justify-content-center align-items-md-center gap-1 container-p-x py-4">
            <div>
                <a href="{{ config('variables.livePreview') }}" target="_blank" class="footer-text fw-bold">Layanan
                    Administrasi Organisasi Mahasiswa</a>
                ©
            </div>
        </div>
    </footer>
</section>
<!--/ Basic footer -->
