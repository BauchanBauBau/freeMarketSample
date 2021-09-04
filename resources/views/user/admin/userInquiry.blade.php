@extends('layouts.top')

<link href="{{ asset('css/userInquiry.css') }}" rel="stylesheet">
<script src="{{ asset('js/status.js') }}" defer></script>
<title>お問い合わせ</title>

@section('content')

<div class="inquiryArea">
    <div class="row">
        <div class="col-md-12">
            <h1>お問い合わせ【{{ $user->nickName }}様】</h1>
            <form action="{{ action('userController@userInquiryPost', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
                <textarea class="form-control" name="inquiry" id="inquiry" rows="5" cols="5" placeholder="お問い合わせ内容を入力してください．" required></textarea>
                <div class="inquiryButton">
                    <button type="submit" class="btn btn-danger" onClick="postAlert(event);return false;">お問い合わせ内容を登録する</button>
                </div>
            </form>
            
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
                                @if(!isset($inquiry->user->nickName))
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
                                @if(!isset($inquiry->user->nickName))
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