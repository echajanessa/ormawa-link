@extends('layouts/contentNavbarLayout')

@section('title', 'Pemantauan Proses')

@section('content')
    <!-- Hoverable Table rows -->
    <div class="card">
        <h5 class="card-header">Pemantauan Proses</h5>
        <div class="table-responsive text-nowrap">
            <table id="myTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>Nomor Dokumen</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Berakhir</th>
                        <th>Jenis Dokumen</th>
                        <th>Status</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0" id="table-body">
                    <script>
                        showLoadingProg(true);
                    </script>
                    @forelse ($documents as $document)
                        <tr>
                            <td>{{ $document->submission_id }}</td>
                            <td>{{ $document->event_name }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($document->start_date)->format('d M Y') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($document->end_date)->format('d M Y') }}</td>
                            <td>{{ $document->doc_type_name }}</td>
                            <td>
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
                                            $badgeClass = 'bg-label-danger';
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
                                <span class="badge {{ $badgeClass }} me-1">{{ $document->doc_status }}</span>
                            </td>
                            <td class="text-center">

                                <a href="{{ route('tracking-detail', $document->submission_id) }}"
                                    class="btn btn-xs btn-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada dokumen yang diajukan</td>
                        </tr>
                    @endforelse

                    <script>
                        showLoadingProg(false);
                    </script>
                </tbody>
            </table>
        </div>
    </div>
    <nav aria-label="Page navigation">
        <div class="ms-2 d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div class="align-items-center mt-6">
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
            <ul class="pagination pagination-sm justify-content-between mt-6">
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
@endsection

<script>
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
