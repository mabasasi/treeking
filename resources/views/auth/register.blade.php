@extends('layouts.treeking')
@section('title', '新規登録')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">

            @component('parts.general-card-component')
                @slot('header')
                    {{ config('app.name', 'Laravel') }}&nbsp;&nbsp;新規登録
                @endslot

                {{ Form::open(['id' => 'register-form', 'method' => 'POST', 'url' => route('register')]) }}

                @component('parts.inline-form-component',['name' => 'name', 'label' => 'ユーザー名'])
                    {{ Form::text('name', old('name'), ['class' => 'form-control', 'autofocus']) }}
                @endcomponent

                @component('parts.inline-form-component',['name' => 'userid', 'label' => 'ユーザーID'])
                    {{ Form::text('userid', old('userid'), ['class' => 'form-control', 'autofocus']) }}
                @endcomponent

                @component('parts.inline-form-component',['name' => 'email', 'label' => 'メールアドレス'])
                    {{ Form::email('email', old('email'), ['class' => 'form-control', 'autofocus']) }}
                @endcomponent

                @component('parts.inline-form-component',['name' => 'password', 'label' => 'パスワード'])
                    {{ Form::password('password', ['class' => 'form-control']) }}
                @endcomponent

                @component('parts.inline-form-component',['name' => 'password_confirmation', 'label' => '(再度入力)'])
                    {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                @endcomponent

                @component('parts.group-form-component')
                    {{ Form::submit('作成する', ['class' => 'btn btn-primary']) }}
                    <a class="btn btn-link" href="{{ route('login') }}">既にアカウントを持っている</a>

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
        var phases = ['name', 'userid', 'email', 'password', 'password_confirmation'];
        var formName = '#register-form';

        // ログイン画面支援
        $(window).keydown(function(e) {
            if(e.keyCode === 13) {
                var name = $(e.target).attr('name');
                var idx  = phases.indexOf(name);

                if (phases.length-1 >= (idx+1)) {
                    var newName = phases[idx+1];
                    console.log(newName);
                    $('[name="'+newName+'"]').focus();
                    return false;
                } else {
                    $(formName).submit();
                    return false;
                }
            }
        });

    </script>
@endpush
