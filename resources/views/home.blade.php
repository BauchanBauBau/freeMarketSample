@extends('layouts.top')

<link href="{{ asset('css/auth.css') }}" rel="stylesheet">

@section('content')
<div class="loggedIn" id="header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if(!isset(Auth::user()->email_verified_at))
                            （注意）本登録はまだ完了していません．<br>
                            登録時に入力いただいたメールアドレスに送信されたメールから<br>
                            本登録を完了させてください．
                        @else    
                            <p>
                                <a class="navbar-brand" href="{{ action('itemController@itemIndex') }}">
                                    ログインしました．
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
