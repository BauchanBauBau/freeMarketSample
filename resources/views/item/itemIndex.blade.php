@extends('layouts.top')

<link href="{{ asset('css/item/itemIndex.css') }}" rel="stylesheet">
<title>Home 商品一覧</title>

@section('content')
@if (!Auth::id())
<div class="bbs">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <p>ご覧いただきありがとうございます．</p>
            <p>商品の購入機能，商品へのコメント投稿機能をお試しいただく場合，
                お手数ですが<a class="nav-link" href="{{ route('register') }}" style="display: inline">ユーザー登録</a>
                及びメールでのユーザー認証を<br>お願いいたします
                （ご登録いただいたメールアドレスは，相手からコメントやメッセージがあった場合の通知に利用いたします）．
            </p>
            <p>本サイトは以下のような仕様のため，安心してお試しいただけます．</p>
            <p>・決済機能は搭載しておらず，料金は一切かかりません．</p>
            <p>・商品購入後の出品者とのやりとりをスキップするボタンを用意しております．</p>
            <p>お問い合わせは，ログイン後，ユーザー名が表示されているボタンの「ユーザーページ」
                ==>>「お問い合わせ」からお願いいたします．</p>
        </div>
    </div>
</div>
@endif
<div class="menu">
    <div class="row">
        <div class="col-md-12">
            <form action="{{ action('itemController@itemIndex') }}" class="col-md-6 offset-md-3" method="GET">
                <div class="form-group">
                    <label for="status">販売状況</label>
                    <select class="form-control" name="status" id="status">
                        <option value="" selected>選択してください</option>
                        <option value="0" @if($status == 0) selected @endif>販売中</option>
                        <option value="1" @if($status == 1) selected @endif>販売済み</option>
                    </select>
                    <label for="category">カテゴリー</label>
                    <select class="form-control" name="category" id="category">
                        <option value="">選択してください</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @if($categ == $category->id) selected @endif>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-block btn-dark" onclick="a();">表示</button>
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
                        <p class="list-group-item">{{ $item->days }}日以内に<br>{{ $item->user->addressPref }}から発送</p>
                    </div>
                </div>
            </a div>
        @endforeach
    </div>
</div>
@endsection
<script>
function a(){
var val = document.getElementById("status").value
console.log(val)
}

</script>