@extends('layouts.top')

<link rel="stylesheet" href="{{ asset('css/user/userRegisteredItem.css') }}">
<title>出品した商品</title>

@section('content')
@if(count($items) < 1 && ($selectStatus == 0 || $selectStatus == 1 || $selectStatus = 2))
    <div class="select">
        @if($selectStatus == 0)
            <h2 class="header"><a href="{{ action('userController@userDealingEnd', ['id' => $user->id]) }}">{{ $user->nickName }}</a>様が出品した商品はありません</h2>
        @elseif($selectStatus == 1)
            <h2 class="header"><a href="{{ action('userController@userDealingEnd', ['id' => $user->id]) }}">{{ $user->nickName }}</a>様が出品した商品で「販売中」のものはありません</h2>
        @elseif($selectStatus == 2)
            <h2 class="header"><a href="{{ action('userController@userDealingEnd', ['id' => $user->id]) }}">{{ $user->nickName }}</a>様が出品した商品で「販売済み」のものはありません</h2>
        @endif
        <form action="{{ action('userController@userRegisteredItem', ['id' => $user]) }}">
            <select class="form-control col-md-4 offset-md-4" name="selectStatus" id="selectStatus">
                <option value="0" @if($selectStatus == 0) selected @endif>全て</option>
                <option value="1" @if($selectStatus == 1) selected @endif>販売中</option>
                <option value="2" @if($selectStatus == 2) selected @endif>販売済み</option>
            </select>
            <button type="submit" class="btn btn-primary col-md-4 offset-md-4">表示</button>
        </form>
    </div>
@else
    <div class="select">
        <h2><a href="{{ action('userController@userDealingEnd', ['id' => $user->id]) }}">{{ $user->nickName }}</a>
            様が出品した商品
            @if($selectStatus == 0)（全て）
            @elseif($selectStatus == 1)（販売中）
            @elseif($selectStatus == 2)（販売済み）
            @endif
            【{{ count($items) }}個】
        </h2>
        <h4>
            @if($good + $bad != 0)
            （
            評価（購入・販売）
            【良い：{{ $good }}件（{{ number_format($good / ($good + $bad) * 100, 0) }}％）】
            【悪い：{{ $bad }}件（{{ number_format($bad / ($good + $bad) * 100, 0) }}％）】
            ）
            @endif
        </h4>
        <form action="{{ action('userController@userRegisteredItem', ['id' => $user]) }}">
            <select class="form-control col-md-4 offset-md-4" name="selectStatus" id="selectStatus">
                <option value="0">全て</option>
                <option value="1">販売中</option>
                <option value="2">販売済み</option>
            </select>
            <button type="submit" class="btn btn-primary col-md-4 offset-md-4">表示</button>
        </form>
    </div>

    <div class="row"> 
        @foreach($items as $item)
            {{-- 
                以下のitemはCapp\Item_comment.phpで定義したitemsテーブルを参照するための
                itemメソッドであり，本メソッドの後ろにitemsテーブルのカラム名を指定する． 
            --}}
            <a div class="card-deck col-md-3 mb-3" href="{{ action('itemController@itemDetail', ['id' => $item->id]) }}">
                <div class="card">
                    <div class="cardImg">
                        @if(!isset($item->image))
                            <div class="noImage">
                                @if($item->buyer_id < 1)
                                    <h5>No Image<br>画像がありません</h5>
                                    <p class="list-group-item">{{ number_format($item->price) }}円</p>
                                @else
                                    <h1><strong>Sold out</strong></h1>
                                    <p class="list-group-item">{{ number_format($item->price) }}円</p>
                                @endif
                            </div>
                        @else
                            <div class="img">
                                @if($item->buyer_id < 1)
                                    <img class="img-thumbnail" src="{{ $item->image }}">
                                    <p class="list-group-item">{{ number_format($item->price) }}円</p>
                                @else
                                    <img class="img-thumbnail" src="{{ $item->image }}">
                                    <h1><strong>Sold out</strong></h1>
                                    <p class="list-group-item">{{ number_format($item->price) }}円</p>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="list-group list-group-flush">
                        @if($item->shippingOption == 0)<p class="list-group-item">送料込み</p>
                        @else<p class="list-group-item">着払い</p>
                        @endif

                        <p class="list-group-item">{{ $item->name }}</p>

                        @if($item->condition == 0)<p class="list-group-item">新品・未使用</p>
                            @elseif($item->condition == 1)<p class="list-group-item">新品・未使用に近い</p>
                            @elseif($item->condition == 2)<p class="list-group-item">目立った傷や汚れ無し</p>
                            @elseif($item->condition == 3)<p class="list-group-item">傷や汚れ有り</p>
                            @elseif($item->condition == 4)<p class="list-group-item">全体的に状態が悪い</p>
                        @endif
                        <p class="list-group-item">{{ $item->days }}日以内に<br>{{ $item->userAddress }}から発送</p>
                    </div>
                </div>
            </a div>
        @endforeach
    </div>
@endif
@endsection