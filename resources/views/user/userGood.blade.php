@extends('layouts.top')

<link rel="stylesheet" href="{{ asset('css/item/itemIndex.css') }}">

@section('content')
<br>
<h1 id="header1">「いいね」した商品一覧
@if(count($goodItems) > 0)（{{ count($goodItems) }}件）</h1>
@elseif(count($goodItems) < 1)
    <h2 id="header2">「いいね」した商品はありません</h2>
@endif
@if(count($goodItems) > 0)
    <div class="row">
        @foreach($goodItems as $goodItem)
            <a div class="card-deck col-md-3 mb-3" href="{{ action('itemController@itemDetail', ['id' => $goodItem->item->id]) }}">
                <div class="card">
                    <div class="cardImg">
                        @if(!isset($goodItem->item->image))
                            <div class="noImage">
                                @if($goodItem->item->buyer_id < 1)
                                    <h5>No Image<br>画像がありません</h5>
                                    <p class="list-group-item">{{ number_format($goodItem->item->price) }}円</p>
                                @else
                                    <h1><strong>Sold out</strong></h1>
                                    <p class="list-group-item">{{ number_format($goodItem->item->price) }}円</p>
                                @endif
                            </div>
                        @else
                            <div class="img">
                                @if($goodItem->item->buyer_id < 1)
                                    <img class="img-thumbnail" src="{{ asset('storage/image/' . $goodItem->item->image) }}">
                                    <p class="list-group-item">{{ number_format($goodItem->item->price) }}円</p>
                                @else
                                    <img class="img-thumbnail" src="{{ asset('storage/image/' . $goodItem->item->image) }}">
                                    <h1><strong>Sold out</strong></h1>
                                    <p class="list-group-item">{{ number_format($goodItem->item->price) }}円</p>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="list-group list-group-flush">
                        @if($goodItem->item->shippingOption == 0)<p class="list-group-item">送料込み</p>
                        @else<p class="list-group-item">着払い</p>
                        @endif

                        <p class="list-group-item">{{ $goodItem->item->name }}</p>

                        @if($goodItem->item->condition == 0)<p class="list-group-item">新品・未使用</p>
                            @elseif($goodItem->item->condition == 1)<p class="list-group-item">新品・未使用に近い</p>
                            @elseif($goodItem->item->condition == 2)<p class="list-group-item">目立った傷や汚れ無し</p>
                            @elseif($goodItem->item->condition == 3)<p class="list-group-item">傷や汚れ有り</p>
                            @elseif($goodItem->item->condition == 4)<p class="list-group-item">全体的に状態が悪い</p>
                        @endif
                        <p class="list-group-item">{{ $goodItem->item->days }}日以内に<br>{{ $goodItem->item->userAddress }}から発送</p>
                    </div>
                </div>
            </a div>
        @endforeach
    </div>
@endif
@endsection