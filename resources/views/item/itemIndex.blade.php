@extends('layouts.top')

<title>Home 商品一覧</title>
<link href="{{ asset('css/item/itemIndex.css') }}" rel="stylesheet">

@section('content')
<div class="menu">
    <div class="row">
        <div class="col-md-12">
            <form action="{{ action('itemController@itemIndex') }}" class="col-md-6 offset-md-3" method="GET">
                @csrf
                    <div class="form-group">
                        <label for="status">販売状況</label>
                        <select class="form-control" name="status" id="status">
                            <option value="" @if(old('status') == null) selected  @endif>>選択してください</option>
                            <option value="0" @if(old('status') == 0) selected  @endif>販売中</option>
                            <option value="1" @if(old('status') == 1) selected  @endif>販売済み</option>
                        </select>
                        <label for="category">カテゴリー</label>
                        <select class="form-control" name="category" id="category">
                            <option value="" @if(old('category') == null) selected  @endif>選択してください</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-block btn-dark">表示</button>
                    </div>
            </form>
        </div>
    </div>
</div>
<div class="itemIndex" style="margin-top: 3%">
    <div class="row">

            @foreach($items as $item)
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
                                    <img class="img-thumbnail" src="{{ asset('storage/image/' . $item->image) }}">
                                    <p class="list-group-item">{{ number_format($item->price) }}円</p>
                                @else
                                    <img class="img-thumbnail" src="{{ asset('storage/image/' . $item->image) }}">
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
</div>
@endsection