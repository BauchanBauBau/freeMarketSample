@extends('layouts.top')

<script src="{{ asset('js/item.js') }}" defer></script>
<title>商品登録</title>

@section('content')

<br>
<h1 style="text-align:center">商品登録</h1>
@if ($errors->any())
<div class="alert text-white bg-danger">
    @foreach ($errors->all() as $error)
        <h5>・{{ $error }}</h5>
    @endforeach
</div>
@endif
<form action="{{ action('itemController@itemRegisterPost') }}" method="post" enctype="multipart/form-data">
@csrf
  <div class="form-group">
    <label for="name">商品名 <span class="badge badge-danger">必須</span></label>
    <input type="text" class="form-control" name="name" id="name" placeholder="商品名" value="{{ old('name') }}" required>
  </div>
  <div class="form-group row">
    <label class="col-md-2" for="image">商品画像</label>
    <br>
    <div class="col-md-12">
      <input type="file" class="form-control-file" id="image" name="image">
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-3">
      <label for="category_id">カテゴリー選択 <span class="badge badge-danger">必須</span></label>
      <select class="form-control" name="category_id" id="category_id">
        @foreach($categories as $category)
          <option value="{{ $category->id }}" @if(old('category_id') == $category->id) selected @endif>{{ $category->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="description">商品説明</label>
    <textarea class="form-control" name="description" id="description" rows="10" cols="10" placeholder="商品についての説明を400字以内で入力してください（必須ではありません）">{{ old('description') }}</textarea>
  </div>
  <div class="form-group row">
    <div class="col-md-3">
      <label for="condition">商品の状態 <span class="badge badge-danger">必須</span></label>
      <select class="form-control" name="condition" id="condition">
        <option value="0" @if(old('condition') == 0) selected @endif>新品・未使用</option>
        <option value="1" @if(old('condition') == 1) selected @endif>新品・未使用に近い</option>
        <option value="2" @if(old('condition') == 2) selected @endif>目立った傷や汚れ無し</option>
        <option value="3" @if(old('condition') == 3) selected @endif>傷や汚れ有り</option>
        <option value="4" @if(old('condition') == 4) selected @endif>全体的に状態が悪い</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-2">
      <label for="price">販売価格(単位：円) <span class="badge badge-danger">必須</span></label>
      <input type="number" class="form-control" name="price" id="price" value="{{ old('price') }}" required>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-3">
      <label for="shippingOption">送料 <span class="badge badge-danger">必須</span></label>
      <select class="form-control" name="shippingOption" id="shippingOption" onchange="change()">
        <option value="0" @if(old('shippingOption') == 0) selected @endif>送料込み</option>
        <option value="1" @if(old('shippingOption') == 1) selected @endif>着払い</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-4">
      <label for="shippingMethod">配送方法 <span class="badge badge-danger">必須</span></label>
      <select class="form-control" name="shippingMethod" id="shippingMethod" onchange="change2()">
        <option value="0" @if(old('shippingMethod') == 0) selected @endif>普通郵便</option>
        <option value="1" @if(old('shippingMethod') == 1) selected @endif>レターパック</option>
        <option value="2" @if(old('shippingMethod') == 2) selected @endif>ゆうパック</option>
        <option value="3" @if(old('shippingMethod') == 3) selected @endif>ヤマト運輸</option>
        <option value="4" @if(old('shippingMethod') == 4) selected @endif>佐川急便</option>
        <option value="5" @if(old('shippingMethod') == 5) selected @endif>その他(荷物の位置を特定できる伝票番号あり)</option>
        <option value="6" @if(old('shippingMethod') == 6) selected @endif>その他(荷物の位置を特定できる伝票番号なし)</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-2">
      発送元：{{ Auth::user()->addressPref }}
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-2">
      <label for="days">発送までの日数 <span class="badge badge-danger">必須</span></label>
      <select class="form-control" name="days" id="days">
        <option value="1" @if(old('days') == 1) selected @endif>1日</option>
        <option value="2" @if(old('days') == 2) selected @endif>2日</option>
        <option value="3" @if(old('days') == 3) selected @endif>3日</option>
        <option value="4" @if(old('days') == 4) selected @endif>4日</option>
        <option value="5" @if(old('days') == 5) selected @endif>5日</option>
        <option value="6" @if(old('days') == 6) selected @endif>6日</option>
        <option value="7" @if(old('days') == 7) selected @endif>7日</option>
      </select>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-12">
        <button type="submit" class="btn btn-danger" onClick="registerAlert(event);return false;">出品する</button>
    </div>
  </div>
</form>
@endsection