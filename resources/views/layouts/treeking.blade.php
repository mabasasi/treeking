@extends('layouts.frame')

@section('body')
    <!-- Navivation Bar -->
    @includeIf('layouts.navbar')

    @if(request('error') == 'true')
        <!-- Debug Error View-->
        @includeIf('layouts.error')
    @endif

    <!-- Main Content -->
    @yield('content')
@endsection


{{--デバッグ用リロード JS--}}
@if(request('reload') > 0)
    @push('scripts')
        <script>
            setTimeout(function () {
                window.location.reload();
            }, "{{ request('reload') }}");
        </script>
    @endpush
@endif