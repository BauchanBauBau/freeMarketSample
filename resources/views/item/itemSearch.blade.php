@extends('layouts.top')

<link href="{{ asset('css/item/itemIndex.css') }}" rel="stylesheet">
<title>商品検索</title>

@section('content')

<div class="search">
    <div class="row">
        <div class="col-md-12">
            <form action="{{ action('itemController@itemSearch') }}" class="col-md-6 offset-md-3" method="GET">
                <div class="form-group">
                    <label for="name">商品名</label>
                    <input class="form-control" type="search" name="name" id="name" value="{{ $name }}" placeholder="商品名">
                </div>
                <button type="button" class="btn btn-block btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    <label for="shippingNumber"><strong>検索詳細設定</strong></label>
                </button>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body">
                        <div class="form-group">
                            <label for="condition">商品の状態</label>
                            <select class="form-control" name="condition" id="condition">
                                <option value="null" @if($condition == "null") selected @endif>選択してください</option>
                                <option value="0" @if($condition == 0) selected @endif>新品・未使用</option>
                                <option value="1" @if($condition == 1) selected @endif>新品・未使用に近い</option>
                                <option value="2" @if($condition == 2) selected @endif>目立った傷や汚れ無し</option>
                                <option value="3" @if($condition == 3) selected @endif>傷や汚れ有り</option>
                                <option value="4" @if($condition == 4) selected @endif>全体的に状態が悪い</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">販売状況</label>
                            <select class="form-control" name="status" id="status">
                                <option value="null" @if($status == "null") selected @endif>選択してください</option>
                                <option value="0" @if($status == 0) selected @endif>販売中</option>
                                <option value="1" @if($status == 1) selected @endif>販売済み</option>
                            </select>
                        </div>
  
                        <div class="price row">
                            <div class="form-group  col-md-6">
                                <label for="priceMin">最低価格</label>
                                <input class="form-control" type="number" placeholder="最低価格" id="priceMin" name="priceMin" value="{{ $priceMin }}">
                            </div>
                            <div class="form-group  col-md-6">
                                <label for="priceMax">最高価格</label>
                                <input class="form-control" type="number" placeholder="最高価格" id="priceMax" name="priceMax" value="{{ $priceMax }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="sellerName">出品者名</label>
                            <input class="form-control" type="search" name="sellerName" id="sellerName" placeholder="出品者名" value="{{ $sellerName }}">
                        </div>
                    </div>
                </div>       
                <button class="btn btn-block btn-success" type="submit">検索</button>
            </form>
        </div>
        @if(isset($items))
            @foreach($items as $item)
                <a div class="card-deck col-md-3 mb-3" href="{{ action('itemController@itemDetail', ['id' => $item->id]) }}">
                    <div class="card">
                        <div class="cardImg">
                            @if(!isset($item->image))
                            <div class="noImage">
                                @if($item->buyer == 0)
                                    <h5>No Image<br>画像がありません</h5>
                                    <p class="list-group-item">{{ number_format($item->price) }}円</p>
                                @else
                                    <h1><strong>Sold out</strong></h1>
                                    <p class="list-group-item">{{ number_format($item->price) }}円</p>
                                @endif
                            </div>
                            @else
                            <div class="img">
                                @if($item->buyer == 0)
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
        @endif
    </div>
</div>

@endsection