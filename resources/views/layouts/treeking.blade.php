@extends('layouts.frame')

@section('body')
    <!-- Navivation Bar -->
    @includeIf('layouts.navbar')

    <!-- Main Content -->
    @yield('content')
@endsection