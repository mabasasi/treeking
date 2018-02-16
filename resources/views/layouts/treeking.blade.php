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