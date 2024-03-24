<div id="loading">
    @include('partials._body_loader')
</div>
@include('partials._body_header')

@include('partials._body_sidebar')

<div id="remoteModelData" class="modal fade" role="dialog"></div>
<div class="content-page">
    {{ $slot }}
</div>

@include('partials._body_footer')

@include('partials._scripts')
@include('partials._dynamic_script')
