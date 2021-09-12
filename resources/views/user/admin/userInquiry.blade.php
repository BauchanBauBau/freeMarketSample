@extends('layouts.top')

<link href="{{ asset('css/userInquiry.css') }}" rel="stylesheet">
<script src="{{ asset('js/status.js') }}" defer></script>
<title>お問い合わせ</title>

@section('content')

<div class="inquiryArea">
    <div class="row">
        <div class="col-md-12">
            <h1>お問い合わせ</h1>
            <h4>
                （
                @if (Auth::user()->role_id == 1)
                   id：{{ $user->id }}番 ，
                @endif
                    {{ $user->nickName }}様
                ）
            </h4>
            <form action="{{ action('userController@userInquiryPost', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
                <textarea class="form-control" name="inquiry" id="inquiry" rows="5" cols="5" placeholder="お問い合わせ内容を入力してください．" required></textarea>
                <div class="inquiryButton">
                    <button type="submit" class="btn btn-danger" onClick="postAlert(event);return false;">お問い合わせ内容を登録する</button>
                </div>
            </form>
            
        @if(count($inquiries) < 1)
            <h4>メッセージはありません</h4>
        @else
            <div class="status">
                <form action="{{ action('userController@userInquiryGet', ['id' => $user->id]) }}" method="GET">
                    <label for="status">メッセージの並び順</label>
                    <select name="status" id="status">
                        <option value="0" @if($status == 0) selected @endif>新しい順</option>
                        <option value="1" @if($status == 1) selected @endif>古い順</option>
                    </select>
                    <button type="submit">並び替え</button>
                </form>
            </div>
        @endif

            <div class="row">
                <div class="inquiries col-md-12">
                    @foreach($inquiries as $inquiry)
                        @if($inquiry->user_id == Auth::id())
                            <div class="rightInquiries col-md-6 offset-md-6">
                                @if(isset($inquiry->user_id))
                                    <h5>{{ $inquiry->inquiry }}</h5>
                                @else
                                    <h5>メッセージを削除しました．</h5>
                                @endif
                                    {{-- 以下のuserはapp\Dealing_inquiry.phpで定義したusersテーブルを参照するための
                                    userメソッドであり，nickNameはusersテーブルのカラムである． --}}
                                @if(!isset($inquiry->user->id))
                                    <p>このユーザーは削除されました．</p>
                                @else
                                    <p>{{ $inquiry->user->nickName }}
                                        <strong>
                                            @if($inquiry->user_id == $superUser->id)（管理者）
                                            @else（お客様）
                                            @endif
                                        </strong>
                                    </p>
                                @endif
                                <p>
                                    @if($inquiry->kidoku > 0)
                                        <strong>（既読）</strong>
                                    @endif
                                    {{ $inquiry->created_at }}
                                </p>
                            </div>
                        @else
                            <div class="leftInquiries col-md-6">
                                @if(isset($inquiry->user_id))
                                    <h5>{{ $inquiry->inquiry }}</h5>
                                @else
                                    <h5>メッセージを削除しました．</h5>
                                @endif
                                {{-- 以下のuserはapp\Dealing_inquiry.phpで定義したusersテーブルを参照するための
                                userメソッドであり，nickNameはusersテーブルのカラムである． --}}
                                @if(!isset($inquiry->user->id))
                                    <p>ユーザー情報は削除されました．</p>
                                @else
                                    <p>{{ $inquiry->user->nickName }}
                                        <strong>
                                            @if($inquiry->user_id == $superUser->id)（管理者）
                                            @else（お客様）
                                            @endif
                                        </strong>
                                    </p>
                                @endif
                                <p>
                                    @if($inquiry->kidoku > 0)
                                        <strong>（既読）</strong>
                                    @endif
                                    {{ $inquiry->created_at }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection