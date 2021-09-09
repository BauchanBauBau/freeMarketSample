@extends('layouts.top')

<link rel="stylesheet" href="{{ asset('css/user/userEdit.css') }}">
<script src="{{ asset('js/user.js') }}" defer></script>
<script src="https://cdn.jsdelivr.net/npm/fetch-jsonp@1.1.3/build/fetch-jsonp.min.js"></script>
<title>ユーザー情報編集</title>

@section('content')
    <div class="table">
        <div class="row">
            <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <h1>ユーザー情報編集</h1>
                <form action="{{ action('userController@userEditPost', ['id' => Auth::id()]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <table class="table table-bordered col-md-8 offset-md-2">
                        <tbody>
                            <tr>
                                <td>登録事項<br>（現在の登録内容）</td>
                                <td>編集後に登録する内容</td>
                            </tr>
                            <tr>
                                <td>ユーザー名<br>（{{ $user->name }}）</td>
                                <td><input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}" required></td>
                            </tr>
                            <tr>
                                <td>ニックネーム<br>～サイト内でのお名前～<br>{{ $user->nickName }}</td>
                                <td><input type="text" class="form-control" name="nickName" id="nickName" value="{{ $user->nickName }}" required></td>
                            </tr>
                            <tr>
                                <td>性別
                                    <br>
                                    （@if($user->gender == 0)選択しない
                                    @elseif($user->gender == 1)男性
                                    @elseif($user->gender == 2)女性
                                    @endif）
                                </td>
                                <td>      
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="0" @if($user->gender == 0) selected @endif>選択しない</option>
                                        <option value="1" @if($user->gender == 1) selected @endif>男性</option>
                                        <option value="2" @if($user->gender == 2) selected @endif>女性</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>メールアドレス<br>（{{ $user->email }}）</td>
                                <td><input type="email" class="form-control" name="email" id="email" value="{{ $user->email }}" required></td>
                            </tr>
                            <tr>
                                <td>電話番号<br>（{{ $user->telNumber }}）</td>
                                <td><input id="telNumber" type="tel" class="form-control" name="telNumber" value="{{ $user->telNumber }}" required></td>
                            </tr>
                            <tr>
                                <td>郵便番号<br>（〒{{ $user->postalCode }}）</td>
                                <td>
                                    <input id="postalCode" type="text" class="form-control" name="postalCode" value="{{ $user->postalCode }}" required>
                                    <button id="search" type="button" class="btn btn-block btn-secondary">住所検索</button>
                                    <p id="error"></p>
                                </td>
                            </tr>
                            <tr>
                                <td>住所<br>～都道府県～<br>（{{ $user->addressPref }}）</td>
                                <td><input id="addressPref" type="text" class="form-control" name="addressPref" value="{{ $user->addressPref }}" required readonly></td>
                            </tr>
                            <tr>
                                <td>住所<br>～市町村～<br>（{{ $user->addressCity }}）</td>
                                <td><input id="addressCity" type="text" class="form-control" name="addressCity" value="{{ $user->addressCity }}" required readonly></td>
                            </tr>
                            <tr>
                                <td>住所<br>～番地等～<br>（{{ $user->addressOther }}）</td>
                                <td><input id="addressOther" type="text" class="form-control" name="addressOther" value="{{ $user->addressOther }}" required></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="submit" class="btn btn-success" value="編集したユーザー情報を登録する" onClick="updateAlert(event);return false;">
                </form>
            </div>
        </div>
    </div>
@endsection