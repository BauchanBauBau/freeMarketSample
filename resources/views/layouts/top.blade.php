<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<header class="sticky-top">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="col-md-4">
                        <a class="navbar-brand" href="{{ action('itemController@itemIndex') }}">フ・リ・マ☆Sample</a>
                    </div>
                    <div class="col-md-4">
                        @if(!Request::routeIs('itemSearch'))
                            <a div href="{{ action('itemController@itemSearch') }}" class="form-inline">
                                <input class="form-control" type="search" placeholder="検索" name="search" disabled>
                                <button class="btn btn-outline-success" type="submit" disabled>検索</button>
                            </a div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <ul class="navbar-nav mr-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">ユーザー登録</a>
                                    </li>
                                @endif
                            @else
                                <div class="dropdown">
                                    <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->nickName }} 様
                                        @if(Auth::user()->role_id == 1)
                                            <br><strong>（管理ユーザー）</strong>
                                        @endif
                                    </a>
                                
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        @if(Auth::user()->role_id == 1)
                                            <a class="dropdown-item" href="{{ route('userIndex') }}">ユーザー一覧</a>
                                        @endif
                                        <a class="dropdown-item" href="{{ action('userController@userPage', ['id' => Auth::id()]) }}">ユーザー情報</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                            ログアウト
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </div>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ action('itemController@itemRegisterGet') }}">出品する</a>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>