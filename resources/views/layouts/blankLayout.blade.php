@extends('layouts/commonMaster')

@section('layoutContent')
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

    <!-- Content -->
    @yield('content')
    <!--/ Content -->
@endsection
