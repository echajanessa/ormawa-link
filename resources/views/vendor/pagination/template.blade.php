<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        @if ($documents->onFirstPage())
            <li class="page-item disabled prev">
                <a class="page-link" href="javascript:void(0);"><i class="tf-icon bx bx-chevrons-left bx-sm"></i></a>
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
                <a class="page-link" href="javascript:void(0);"><i class="tf-icon bx bx-chevrons-right bx-sm"></i></a>
            </li>
        @endif
    </ul>
</nav>
