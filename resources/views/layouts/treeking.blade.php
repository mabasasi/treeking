@extends('layouts.frame')

@section('body')
    <!-- Navivation Bar -->
    @includeIf('layouts.navbar')

    @if(request('error') == 'true')
        <!-- Debug Error View-->
        @includeIf('layouts.error')
    @endif

    <!-- Main Content -->
    @if (View::hasSection('container'))
        <div class="@yield('container')">
            @yield('content')
        </div>
    @else
        <div class="container">
            @yield('content')
        </div>
    @endif
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