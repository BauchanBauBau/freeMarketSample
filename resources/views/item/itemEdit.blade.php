@extends('layouts.top')

<script src="{{ asset('js/item.js') }}" defer></script>
<title>商品情報編集</title>

@section('content')
<br>
<h1 style="text-align:center">商品情報編集</h1>

<form action="{{ action('itemController@itemEditPost', ['id' => $item->id]) }}" method="post" enctype="multipart/form-data">
  @csrf
  <div class="form-group">
    <label for="name">商品名 <span class="badge badge-danger">必須</span></label>
    <input type="text" class="form-control" name="name" id="name" value="{{ $item->name }}">
  </div>

  <div class="form-group row">
    <label class="col-md-2" for="image">画像</label>
    <div class="col-md-10">
        <input type="file" class="form-control-file" name="image" value="{{ $item->image }}">
        <div class="form-text text-info">
            設定中: {{ $item->image }}
        </div>
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" name="remove" value="true">画像を削除
            </label>
        </div>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-3">
      <label for="category_id">カテゴリー選択 <span class="badge badge-danger">必須</span></label>
      <select class="form-control" name="category_id" id="category_id">
        <option value="{{ $selectedCategory->id }}" selected>{{ $selectedCategory->name }}</option>
        @foreach($categories as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="description">商品説明</label>
    <textarea class="form-control" name="description" id="description" rows="10" cols="10">{{ $item->description }}</textarea>
  </div>
  <div class="form-group row">
    <div class="col-md-3">
      <label for="condition">商品の状態 <span class="badge badge-danger">必須</span></label>
      <select class="form-control" name="condition" id="condition">
        <option value="0" @if($item->condition == 0) selected @endif>新品・未使用</option>
        <option value="1" @if($item->condition == 1) selected @endif>新品・未使用に近い</option>
        <option value="2" @if($item->condition == 2) selected @endif>目立った傷や汚れ無し</option>
        <option value="3" @if($item->condition == 3) selected @endif>傷や汚れ有り</option>
        <option value="4" @if($item->condition == 4) selected @endif>全体的に状態が悪い</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-2">
      <label for="price">販売価格（単位：円） <span class="badge badge-danger">必須</span></label>
      <input type="number" class="form-control" name="price" id="price" value="{{ $item->price }}" required>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-3">
      <label for="shippingOption">送料 <span class="badge badge-danger">必須</span></label>
      <select class="form-control" name="shippingOption" id="shippingOption" onchange="change()">
        <option value="0" @if($item->shippingOption == 0) selected @endif>送料込み</option>
        <option value="1" @if($item->shippingOption == 1) selected @endif>着払い</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-4">
      <label for="shippingMethod">配送方法 <span class="badge badge-danger">必須</span></label>
      <select class="form-control" name="shippingMethod" id="shippingMethod" onchange="change()">
        <option value="0" @if($item->shippingMethod == 0) selected @endif>普通郵便</option>
        <option value="1" @if($item->shippingMethod == 1) selected @endif>レターパック</option>
        <option value="2" @if($item->shippingMethod == 2) selected @endif>ゆうパック</option>
        <option value="3" @if($item->shippingMethod == 3) selected @endif>ヤマト運輸</option>
        <option value="4" @if($item->shippingMethod == 4) selected @endif>佐川急便</option>
        <option value="5" @if($item->shippingMethod == 5) selected @endif>その他(荷物の位置を特定できる伝票番号あり)</option>
        <option value="6" @if($item->shippingMethod == 6) selected @endif>その他(荷物の位置を特定できる伝票番号なし)</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-2">
      発送元：{{ $item->user->addressPref }}
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-2">
      <label for="days">発送までの日数 <span class="badge badge-danger">必須</span></label>
      <select class="form-control" name="days" id="days">
        <option value="1" @if($item->days == 1) selected @endif>1日</option>
        <option value="2" @if($item->days == 2) selected @endif>2日</option>
        <option value="3" @if($item->days == 3) selected @endif>3日</option>
        <option value="4" @if($item->days == 4) selected @endif>4日</option>
        <option value="5" @if($item->days == 5) selected @endif>5日</option>
        <option value="6" @if($item->days == 6) selected @endif>6日</option>
        <option value="7" @if($item->days == 7) selected @endif>7日</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-10">
        <button type="submit" class="btn btn-primary" onClick="updateAlert(event);return false;">更新</button>
    </div>
  </div>
</form>
@endsection