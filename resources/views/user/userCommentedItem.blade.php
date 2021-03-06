@extends('layouts.top')

<link rel="stylesheet" href="{{ asset('css/item/itemIndex.css') }}">
<title>コメントした商品</title>

@section('content')
<br>
<h2 id="header1">
    <small>
        （他のユーザーの商品で）
    </small>
    <br>
        コメントした商品一覧
    @if(count($commentedItems) > 0)
        （{{ count($commentedItems) }}件）
    @endif
</h2>
    <div class="selectStatus form-group col-md-4 offset-md-4">
        <form action="{{ action('userController@userCommentedItem', ['id' => $user]) }}">
            <label for="selectStatus">表示内容を選択してください</label>
            <select class="form-control" name="selectStatus" id="selectStatus">
                <option value="0" @if($selectStatus == 0) selected @endif>出品者から新着のコメントが来た商品</option>
                <option value="1" @if($selectStatus == 1) selected @endif>コメントした商品全て</option>
            </select>
            <button type="submit" class="btn btn-block btn-primary">表示</button>
        </form>
    </div>
@if(count($commentedItems) < 1)
    <h4 id="header2">条件に該当する商品はありません</h4>
@endif
<div class="row"> 
    @foreach($commentedItems as $commentedItem)
        {{-- 
            以下のitemはCapp\Item_comment.phpで定義したitemsテーブルを参照するための
            itemメソッドであり，本メソッドの後ろにitemsテーブルのカラム名を指定する． 
        --}}
        <a div class="card-deck col-md-3 mb-3" href="{{ action('itemController@itemDetail', ['id' => $commentedItem->item->id]) }}">
            <div class="card">
                <div class="cardImg">
                    @if(!isset($commentedItem->item->image))
                        <div class="noImage">
                            @if($commentedItem->item->buyer_id < 1)
                                <h5>No Image<br>画像がありません</h5>
                                <p class="list-group-item">{{ number_format($commentedItem->item->price) }}円</p>
                            @else
                                <h1><strong>Sold out</strong></h1>
                                <p class="list-group-item">{{ number_format($commentedItem->item->price) }}円</p>
                            @endif
                        </div>
                    @else
                        <div class="img">
                            @if($commentedItem->item->buyer_id < 1)
                                <img class="img-thumbnail" src="{{ $commentedItem->item->image }}">
                                <p class="list-group-item">{{ number_format($commentedItem->item->price) }}円</p>
                            @else
                                <img class="img-thumbnail" src="{{ $commentedItem->item->image }}">
                                <h1><strong>Sold out</strong></h1>
                                <p class="list-group-item">{{ number_format($commentedItem->item->price) }}円</p>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="list-group list-group-flush">
                    @if($commentedItem->item->shippingOption == 0)<p class="list-group-item">送料込み</p>
                    @else<p class="list-group-item">着払い</p>
                    @endif

                    <p class="list-group-item">{{ $commentedItem->item->name }}</p>

                    @if($commentedItem->item->condition == 0)<p class="list-group-item">新品・未使用</p>
                        @elseif($commentedItem->item->condition == 1)<p class="list-group-item">新品・未使用に近い</p>
                        @elseif($commentedItem->item->condition == 2)<p class="list-group-item">目立った傷や汚れ無し</p>
                        @elseif($commentedItem->item->condition == 3)<p class="list-group-item">傷や汚れ有り</p>
                        @elseif($commentedItem->item->condition == 4)<p class="list-group-item">全体的に状態が悪い</p>
                    @endif
                    <p class="list-group-item">{{ $commentedItem->item->days }}日以内に<br>{{ $commentedItem->item->userAddress }}から発送</p>
                </div>
            </div>
        </a div>
    @endforeach
</div>
@endsection