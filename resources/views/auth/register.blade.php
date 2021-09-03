@extends('layouts.top')

<link href="{{ asset('css/auth.css') }}" rel="stylesheet">
<script src="{{ asset('js/user.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/fetch-jsonp@1.1.3/build/fetch-jsonp.min.js"></script>
<title>ユーザー登録</title>

@section('content')
<div class="userRegister" id="header">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">ユーザー登録</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">名前</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="nickName" class="col-md-4 col-form-label text-md-right">ニックネーム<br>（サイト内でのお名前）</label>

                            <div class="col-md-6">
                                <input id="nickName" type="text" class="form-control @error('nickName') is-invalid @enderror" name="nickName" value="{{ old('nickName') }}" required autocomplete="nickName" autofocus>

                                @error('nickName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">性別</label>
                            <div class="col-md-6">
                                <select class="form-control" id="gender" name="gender">
                                    <option value="0" selected @if(old('name')=='0') selected  @endif>選択しない</option>
                                    <option value="1" @if(old('name')=='1') selected  @endif>男性</option>
                                    <option value="2" @if(old('name')=='2') selected  @endif>女性</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="（例）xxx@yyy.co.jp" autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="telNumber" class="col-md-4 col-form-label text-md-right">電話番号</label>

                            <div class="col-md-6">
                                <input id="telNumber" type="tel" class="form-control @error('telNumber') is-invalid @enderror" name="telNumber" value="{{ old('telNumber') }}" placeholder="（例）03-5321-1111" required autocomplete="telNumber">

                                @error('telNumber')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <label for="postalCode" class="col-md-4 col-form-label text-md-right">郵便番号</label>

                            <div class="col-md-6">
                                <input id="postalCode" type="text" class="form-control @error('postalCode') is-invalid @enderror" name="postalCode" value="{{ old('postalCode') }}" placeholder="（例）123-4567" required autocomplete="postalCode">
                                <button id="search" type="button" class="btn btn-block btn-secondary btn-sm">住所検索</button>
                                <p id="error"></p>
                                @error('postalCode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="addressPref" class="col-md-4 col-form-label text-md-right">住所（都道府県）</label>

                            <div class="col-md-6">
                                <input id="addressPref" type="text" class="form-control @error('addressPref') is-invalid @enderror" name="addressPref" value="{{ old('addressPref') }}" required readonly placeholder="自動入力" autocomplete="addressPref">

                                @error('addressPref')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="addressCity" class="col-md-4 col-form-label text-md-right">住所（市町村）</label>

                            <div class="col-md-6">
                                <input id="addressCity" type="text" class="form-control @error('addressCity') is-invalid @enderror" name="addressCity" value="{{ old('addressCity') }}" required readonly placeholder="自動入力" autocomplete="addressCity">

                                @error('addressCity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="addressOther" class="col-md-4 col-form-label text-md-right">住所（番地等）</label>

                            <div class="col-md-6">
                                <input id="addressOther" type="text" class="form-control @error('addressOther') is-invalid @enderror" name="addressOther" value="{{ old('addressOther') }}" required autocomplete="addressOther">

                                @error('addressOther')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="最低8文字以上" autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">パスワード（再入力）</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-block btn-primary" onClick="registerAlert(event);return false;">
                                    ユーザー登録する
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
