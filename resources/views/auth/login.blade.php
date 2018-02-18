@extends('layouts.treeking')
@section('title', 'ログイン')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">

            @component('parts.general-card-component')
                @slot('header')
                    {{ config('app.name', 'Laravel') }}
                @endslot

                {{ Form::open(['id' => 'login-form', 'method' => 'POST', 'url' => route('login')]) }}

                @component('parts.inline-form-component',['name' => 'userid', 'label' => 'ユーザーID'])
                    {{ Form::text('userid', old('userid'), ['class' => 'form-control', 'autofocus']) }}
                @endcomponent

                @component('parts.inline-form-component',['name' => 'password', 'label' => 'パスワード'])
                    {{ Form::password('password', ['class' => 'form-control']) }}
                @endcomponent

                {{--@component('parts.group-form-component')--}}
                {{--<div class="form-group">--}}
                {{--<div class="col-md-6 col-md-offset-4">--}}
                {{--<div class="checkbox">--}}
                {{--<label>--}}
                {{--<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me--}}
                {{--</label>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--@endcomponent--}}

                @component('parts.group-form-component')
                    {{ Form::submit('ログイン', ['class' => 'btn btn-primary']) }}

                    {{--TODO 今は使わない(面倒くさい...)--}}
                    {{--<a class="btn btn-link" href="{{ route('password.request') }}">パスワードを忘れた...</a>--}}
                @endcomponent

                {{ Form::close() }}
            @endcomponent

        </div>
    </div>
@endsection



@push('scripts')
    <script>

        // ログイン画面支援
        $(window).keydown(function(e) {
            if(e.keyCode === 13) {
                var name = $(e.target).attr('name');
                console.log(name);
                if (name === 'userid') {
                    $('[name="password"]').focus();
                    return false;
                }

                if (name === 'password') {
                    $('#login-form').submit();
                    return false;
                }
                return false;
            }
        });

    </script>
@endpush