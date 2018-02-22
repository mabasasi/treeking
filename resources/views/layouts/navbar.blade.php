<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'Laravel') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <!-- Left Element -->
        <ul class="navbar-nav mr-auto">
            @can('admin')
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('test') }}">テストページ</a>
                </li>
            @endcan

            <li class="nav-item">
                <a class="nav-link" href="{{ route('treeking.index') }}">めも帳</a>
            </li>
        </ul>

        <!-- Right Element -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <span class="navbar-text">
                    @auth
                        branch: {{ optional(\Auth::user()->currentBranch)->name ?? '-' }},&nbsp;&nbsp;
                    @endauth
                    git: {{ git_branch() ?? 'none' }},&nbsp;&nbsp;
                    env: {{ \Config::get('app.env') }} - {{ \Config::get('app.debug') == 'true' ? 'debug' : '' }}&nbsp;&nbsp;
                </span>
            </li>

            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">新規登録</a>
                </li>
            @else
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ optional(\Auth::user())->name }}&nbsp;さん
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="{{ route('home') }}">ホーム</a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            ログアウト
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            @endcan

            <span class="mr-3"></span>

        </ul>
    </div>
</nav>