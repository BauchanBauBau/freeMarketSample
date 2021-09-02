@extends('layouts.top')

<link rel="stylesheet" href="{{ asset('css/user/userInfo.css') }}">
<script src="{{ asset('js/user.js') }}" defer></script>

@section('content')

<div class="table">
    <div class="row">
        <div class="col-md-12">
            <h1>ユーザー情報</h1>
            <table class="table table-bordered col-md-8 offset-md-2">
                <tbody>
                    <tr>
                        <td>ユーザー名</td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td>ニックネーム<br>（サイト内でのお名前）</td>
                        <td>{{ $user->nickName }}</td>
                    </tr>
                    <tr>
                        <td>性別</td>
                        <td>@if($user->gender == 0)選択しない
                            @elseif($user->gender == 1)男性
                            @elseif($user->gender == 2)女性
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>メールアドレス</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td>電話番号</td>
                        <td>{{ $user->telNumber }}</td>
                    </tr>
                    <tr>
                        <td>郵便番号</td>
                        <td>〒{{ $user->postalCode }}</td>
                    </tr>
                    <tr>
                        <td>住所（都道府県）</td>
                        <td>{{ $user->addressPref }}</td>
                    </tr>
                    <tr>
                        <td>住所（市町村）</td>
                        <td>{{ $user->addressCity }}</td>
                    </tr>
                    <tr>
                        <td>住所（番地等）</td>
                        <td>{{ $user->addressOther }}</td>
                    </tr>
                </tbody>
            </table>
            <a href="{{ action('userController@userEditGet', ['id' => Auth::id()]) }}" class="btn btn-success btn-lg btn-block">ユーザー情報を編集する</a>
            @if($dealing < 1)
                <a href="{{ action('userController@userDelete', ['id' => Auth::id()]) }}" class="btn btn-danger" onClick="deleteAlert(event);return false;">ユーザー情報を削除する</a>
            @else
                <h2>取引中の商品があるためユーザー情報を削除することはできません．</h2>
            @endif
        </div>
    </div>
</div>
@endsection