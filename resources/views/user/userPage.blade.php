@extends('layouts.top')

<title>ユーザーページ</title>

@section('content')
<div class="userInfo" style="margin-top: 5%">
    <div class="row">
        <div class="col-md-8 offset-md-4">
            <h1>ユーザー情報</h1>
            <ul style="font-size: 15pt">
                @if(Auth::user()->role_id != 1)
                <li>
                    <a href="{{ action('userController@userInquiryGet', 
                        ['id' => Auth::id()]) }}">お問い合わせ
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ action('userController@userInfo',
                        ['id' => Auth::id()]) }}">ユーザー登録情報
                    </a>
                </li>
                <li>
                    <a href="{{ action('userController@userRegisteredItem',
                        ['id' => Auth::id()]) }}">出品した商品
                    </a>  
                </li>
                <li>
                    <a href="{{ action('userController@userCommentedItem',
                        ['id' => Auth::id()]) }}"><small>（他のユーザーの商品で）</small>コメントした商品 【{{ $commentedItems }}個】
                    </a>
                </li>
                <li>
                    <a href="{{ action('userController@userCommentedItemByWatcher',
                        ['id' => Auth::id()]) }}"><small>（自分の商品で）</small>コメントが来た商品 【{{ $commentedItemsByWatcher }}個】
                    </a>
                </li>
                <li>
                    <a href="{{ action('userController@userGood',
                        ['id' => Auth::id()]) }}">「いいね」した商品 【 {{ $goodItems }}個】
                    </a>
                </li>
                <li>
                    <a href="{{ action('userController@userGoodByWatcher',
                        ['id' => Auth::id()]) }}">「いいね」された商品 【{{ $goodItemsByWatcher }}個】
                    </a>
                </li>
                <li>
                    <a href="{{ action('userController@userDealingBuy',
                        ['id' => Auth::id()]) }}">取引中の商品（購入）【 {{ $dealingStatusBuy }}個】
                    </a>
                </li> 
                <li>
                    <a href="{{ action('userController@userDealingSell',
                        ['id' => Auth::id()]) }}">取引中の商品（販売） 【{{ $dealingStatusSell }}個】
                    </a>
                </li>
                <li>
                    <a href="{{ action('userController@userDealingEnd',
                        ['id' => Auth::id()]) }}">終了した取引
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection