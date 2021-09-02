@extends('layouts.top')

<link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@section('content')
<div class="verify" id="header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">本登録を完了させてください．</div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                ユーザー登録時に登録いただいたメールアドレスに
                                新たに本登録用のメールをお送りしました．
                            </div>
                        @endif

                        以降の処理を実行するには，ユーザー登録時に登録いただいたメールアドレスにお送りした
                        メールから本登録をしていただく必要があります．なお，本登録のメールを受信していない場合は，
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">こちらをクリックください</button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
