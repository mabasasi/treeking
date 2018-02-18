@extends('layouts.treeking')
@section('title', 'めも帳')

@section('content')

    @include('treeking.widget-create-form')

    <hr>

    @include('treeking.widget-tree', ['branch' => $branch])
@endsection