@extends('layouts.top')

@section('content')

<div class="userIndex" style="margin-top: 3%">
    <div class="row">
        <table class="col-md-6 offset-md-3">
            <thead>
                <tr>
                    <th>ユーザー名</th>
                    <th>出品数</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
            </thead>
            @foreach($users as $user)
            <tr>
                <td class="col-md-6">
                    <a href="{{ action('userController@userDealingEnd', ['id' => $user->id]) }}">{{ $user->nickName }}</a>
                </td>
                <td class="col-md-2">
                    うんこ
                </td>
                <td class="col-md-2">
                    <a href="{{ action('userController@userInfo', ['id' => $user->id]) }}" class="btn btn-success">編集</a>
                </td>
                <td class="col-md-2">
                    削除
                </td>
            <tr>
            @endforeach
        </table>
    </div>
</div>

@endsection