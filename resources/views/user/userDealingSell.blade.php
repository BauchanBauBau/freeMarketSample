@extends('layouts.top')

<link rel="stylesheet" href="{{ asset('css/item/itemIndex.css') }}">
<title>取引中の商品</title>

@section('content')
<h1 id="header1">取引中の商品（販売）【{{ count($dealingStatuses) }}件】</h1>
@if(count($dealingStatuses) < 1)
    <h2 id="header2">該当する取引はありません．</h2>
@endif
<div class="selectStatus">
    <form action="{{ action('userController@userDealingSell', ['id' => $user]) }}">
        <select class="form-control col-md-4 offset-md-4" name="selectStatus" id="selectStatus">
            <option value="0" @if($selectStatus == 0) selected @endif>購入者から新着のメッセージが来た商品</option>
            <option value="1" @if($selectStatus == 1) selected @endif>取引中の商品すべて（販売）</option>
        </select>
        <button type="submit" class="btn btn-primary col-md-4 offset-md-4">表示</button>
    </form>
</div>
<div class="row"> 
    {{-- 以下では，他のユーザーが出品した商品に対して現在のユーザーがコメントをしているものを表示する． --}}
    @foreach($dealingStatuses as $dealingStatus)
        {{-- 
            以下（div class="card"から）のitemはapp\Item_comment.phpまたはapp\Dealing_message.phpで定義した
            itemsテーブルを参照するためのitemメソッドであり，本メソッドの後ろに
            itemsテーブルのカラム名を指定する． 
            （「$dealingStatus->item->user_id」とは出品者のidのこと）
        --}}
        <a div class="card-deck col-md-3 mb-3" 
        @if($selectStatus == 0)
            href="{{ action('dealingSellerController@statusSeller', ['id' => $dealingStatus->dealingStatus_id]) }}"
        @elseif ($selectStatus == 1)
            href="{{ action('dealingSellerController@statusSeller', ['id' => $dealingStatus->id]) }}"
        @endif
        >
            <div class="card">
                <div class="cardImg">
                    @if(!isset($dealingStatus->item->id))
                        <div class="noImage">
                            @if($dealingStatus->item->buyer_id < 1)
                                <h5>No Image<br>画像がありません</h5>
                                <p class="list-group-item">{{ number_format($dealingStatus->item->price) }}円</p>
                            @else
                                <h1><strong>Sold out</strong></h1>
                                <p class="list-group-item">{{ number_format($dealingStatus->item->price) }}円</p>
                            @endif
                        </div>
                    @else
                        <div class="img">
                            @if($dealingStatus->item->buyer_id < 1)
                                <img class="img-thumbnail" src="{{ $dealingStatus->item->image }}">
                                <p class="list-group-item">{{ number_format($dealingStatus->item->price) }}円</p>
                            @else
                                <img class="img-thumbnail" src="{{ $dealingStatus->item->image }}">
                                <h1><strong>Sold out</strong></h1>
                                <p class="list-group-item">{{ number_format($dealingStatus->item->price) }}円</p>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="list-group list-group-flush">
                    @if($dealingStatus->item->shippingOption == 0)<p class="list-group-item">送料込み</p>
                    @else<p class="list-group-item">着払い</p>
                    @endif

                    <p class="list-group-item">{{ $dealingStatus->item->name }}</p>

                    @if($dealingStatus->item->condition == 0)<p class="list-group-item">新品・未使用</p>
                        @elseif($dealingStatus->item->condition == 1)<p class="list-group-item">新品・未使用に近い</p>
                        @elseif($dealingStatus->item->condition == 2)<p class="list-group-item">目立った傷や汚れ無し</p>
                        @elseif($dealingStatus->item->condition == 3)<p class="list-group-item">傷や汚れ有り</p>
                        @elseif($dealingStatus->item->condition == 4)<p class="list-group-item">全体的に状態が悪い</p>
                    @endif
                    <p class="list-group-item">{{ $dealingStatus->item->days }}日以内に<br>{{ $dealingStatus->item->userAddress }}から発送</p>
                </div>
            </div>
        </a div>
    @endforeach
</div>
@endsection