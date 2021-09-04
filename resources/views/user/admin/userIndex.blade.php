@extends('layouts.top')

<script src="{{ asset('js/user.js') }}" defer></script>
<title>ユーザー管理</title>
@section('content')

<div class="userIndex" style="margin-top: 3%">
    <div class="row">
        <table class="table table-bordered col-md-10 offset-md-1" style="text-align: center">
            <thead>
                <tr>
                    <th>id</th>
                    <th>ユーザー名</th>
                    <th>出品数
                        <br>
                        （販売中）
                    </th>
                    <th>編集</th>
                    <th>問い合わせ</th>
                    <th>削除</th>
                </tr>
            </thead>
            @foreach($users as $user)
            <tr>
                <td class="col-md-2">
                    {{ $user->id }}
                </td>
                <td class="col-md-2">
                    <a href="{{ action('userController@userDealingEnd', ['id' => $user->id]) }}">{{ $user->nickName }}</a>
                </td>
                <td class="col-md-2">
                    <a href="{{ action('userController@userRegisteredItem', ['id' => $user->id]) }}">{{ $user->items }}個</a>
                </td>
                <td class="col-md-2">
                    <a href="{{ action('userController@userInfo', ['id' => $user->id]) }}" class="btn btn-success">編集</a>
                </td>
                <td class="col-md-2">
                    <a href="{{ action('userController@userInquiryGet', ['id' => $user->id]) }}" class="btn btn-success">問い合わせ</a>
                </td>
                <td class="col-md-2">
                    <form action="{{ action('userController@userDelete', ['id' => $user->id]) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-danger" onClick="deleteAlert(event);return false;">削除</button>
                    </form>
                </td>
            <tr>
            @endforeach
        </table>
    </div>
</div>

@endsection