@extends('layouts.top')

<link href="{{ asset('css/user/userIndex.css') }}" rel="stylesheet">
<script src="{{ asset('js/user.js') }}" defer></script>
<title>ユーザー管理</title>
@section('content')
<div class="menu">
    <div class="row">
        <div class="col-md-12">
            <div class="voyager">
                <a class="btn btn-danger offset-md-5" href="/admin">管理画面（voyager）</a>
                <h5 class="offset-md-3">ユーザーの削除，商品の削除は管理画面（voyager）で行わないこと！</h5>
                <h5 class="offset-md-3">（削除用のメソッドで条件に応じた削除が行われるため）</h5>
            </div>
            <form action="{{ action('userController@userIndex') }}" class="col-md-6 offset-md-3" method="GET">
                <div class="form-group">
                    <label for="status">表示内容を選択してください</label>
                    <select class="form-control" name="status" id="status">
                        <option value="0" @if($status == 0) selected @endif>新着のコメントがあったユーザー</option>
                        <option value="1" @if($status == 1) selected @endif>全てのユーザー（出品数の多い順）</option>
                        <option value="2" @if($status == 2) selected @endif>全てのユーザー（出品数の少ない順）</option>
                        <option value="3" @if($status == 3) selected @endif>全てのユーザー</option>
                    </select>
                    <button type="submit" class="btn btn-block btn-dark">表示</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="userIndex" style="margin-top: 3%">
    <div class="row">
        <table class="table table-bordered col-md-8 offset-md-2" style="text-align: center">
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
                <td class="col-md-1">
                    @if($status < 1)
                        {{ $user->user->id }}
                    @else
                        {{ $user->id }}
                    @endif
                </td>
                <td class="col-md-3">
                    @if($status < 1)
                        <a href="{{ action('userController@userDealingEnd', 
                            ['id' => $user->user->id]) }}">{{ $user->user->nickName }}
                        </a>
                    @else
                        <a href="{{ action('userController@userDealingEnd', 
                            ['id' => $user->id]) }}">{{ $user->nickName }}
                        </a>
                    @endif
                </td>
                <td class="col-md-2">
                    @if($status < 1)
                        <a href="{{ action('userController@userRegisteredItem', 
                            ['id' => $user->user->id]) }}">{{ $user->user->items }}個
                        </a>
                    @else
                        <a href="{{ action('userController@userRegisteredItem', 
                            ['id' => $user->id]) }}">{{ $user->items }}個
                        </a>
                    @endif
                </td>
                <td class="col-md-2">
                    @if($status < 1)
                        <a href="{{ action('userController@userInfo', 
                            ['id' => $user->user->id]) }}" class="btn btn-success">編集
                        </a>
                    @else
                        <a href="{{ action('userController@userInfo', 
                            ['id' => $user->id]) }}" class="btn btn-success">編集
                        </a>
                    @endif
                </td>
                <td class="col-md-2">
                    @if($status < 1)
                        <a href="{{ action('userController@userInquiryGet', 
                            ['id' => $user->user->id]) }}" class="btn btn-success">問い合わせ
                        </a>
                    @else
                        <a href="{{ action('userController@userInquiryGet', 
                            ['id' => $user->id]) }}" class="btn btn-success">問い合わせ
                        </a>
                    @endif
                </td>
                <td class="col-md-2">
                    @if($status < 1)
                        <form action="{{ action('userController@userDelete', ['id' => $user->user->id]) }}" method="post">
                    @else
                        <form action="{{ action('userController@userDelete', ['id' => $user->id]) }}" method="post">
                    @endif
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