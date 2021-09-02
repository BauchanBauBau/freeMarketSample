@extends('layouts.top')

<link href="{{ asset('css/item/itemDetail.css') }}" rel="stylesheet">
<script src="{{ asset('js/item.js') }}" defer></script>

@section('content')
<div class="name">
  <div class="row">
    <div class="col-md-12">
    <h1>{{ $itemDetail->name }}</h1>
    </div>
  </div>
</div>
@if($itemDetail->buyer_id > 0)
<div class="soldOut">
  <h1><strong>Sold Out</strong></h1>
</div>
@endif
<br>
<div class="info">
  <div class="row">
    <div class="col-md-6">
      <div class="img">
        @if(isset($itemDetail->image))
          <img class="img-thumbnail" src="{{ asset('storage/image/' . $itemDetail->image) }}">
        @else
          <div class="noImage">
            <h2 id="noImage0">No Image</h2>
            <h2 id="noImage1">画像がありません</h2>
          </div>
        @endif
      </div>
    </div>
    <div class="col-md-6">
      <div class="table">
        <table class="table table-bordered">
          <h1>商品情報</h1>
          <tbody>
            <tr>
              <td>出品者</td>
              <td>
                @if(!isset($itemDetail->user->nickName))
                  出品したユーザー情報は削除されました．
                @else
                  <a href="{{ action('userController@userRegisteredItem',
                    ['id' => $itemDetail->user_id]) }}">{{ $itemDetail->user->nickName }}
                  </a>
                @endif
              </td>
            </tr>
            <tr>
              <td>出品者の評価</td>
              <td>
                {{-- 「$itemDetail->user->nickName」が存在しなければ出品したユーザー情報も存在しない --}}
                @if(isset($itemDetail->user->nickName))
                  @if($goodDealing + $badDealing > 0)
                    【良い：{{ $goodDealing }}件（{{ round($goodDealing / ($goodDealing + $badDealing) * 100, 0) }}％）】
                    【悪い：{{ $badDealing }}件（{{ round($badDealing / ($goodDealing + $badDealing) * 100, 0) }}）％】
                  @elseif($goodDealing + $badDealing < 1)
                    まだ評価がありません．
                  @endif
                @else
                  出品したユーザー情報は削除されました．
                @endif
              </td>
            </tr>
            <tr>
              <td>カテゴリ</td>
              <td>{{ $category->name }}</td>
            </tr>
            <tr>
              <td>商品の状態</td>
              <td>
                @if($itemDetail->condition == 0)新品・未使用
                  @elseif($itemDetail->condition == 1)新品・未使用に近い
                  @elseif($itemDetail->condition == 2)目立った傷や汚れ無し
                  @elseif($itemDetail->condition == 3)傷や汚れ有り
                  @elseif($itemDetail->condition == 4)全体的に状態が悪い
                @endif
              </td>
            </tr>
            <tr>
              <td>送料</td>
              <td>
                @if($itemDetail->shippingOption == 0)送料込み
                  @else 着払い
                @endif
              </td>
            </tr>
            <tr>
              <td>発送方法</td>
              <td>
                @if($itemDetail->shippingMethod == 0)普通郵便
                @elseif($itemDetail->shippingMethod == 1)レターパック
                @elseif($itemDetail->shippingMethod == 2)ゆうパック
                @elseif($itemDetail->shippingMethod == 3)ヤマト運輸
                @elseif($itemDetail->shippingMethod == 4)佐川急便
                @elseif($itemDetail->shippingMethod == 5)その他(荷物の位置を特定できる伝票番号あり)
                @elseif($itemDetail->shippingMethod == 6)その他(荷物の位置を特定できる伝票番号なし)
                @endif
              </td>
            </tr>
            <tr>
              <td>発送元地域</td>
              <td>
                @if(!isset($itemDetail->user->addressPref))
                  出品したユーザー情報は削除されました．
                @else
                  {{ $itemDetail->user->addressPref }}
                @endif
              </td>
            </tr>
            <tr>
              <td>発送の目安</td>
              <td>{{ $itemDetail->days }}日</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<br>
<div class="price">
  <div class="row">
    <div class="col-md-12">
      <h1>￥{{ number_format($itemDetail->price) }}</h1>
      <h5>（税込）</h5>
      <h5>
        @if($itemDetail->shippingOption == 0)送料込み
        @else 着払い
        @endif
      </h5>
      
      @if($itemDetail->user_id != Auth::id() && Auth::user()->role_id != 1)
        @if($itemDetail->buyer_id < 1)
          <a href="{{ action('dealingBuyerController@buy',
            ['id' => $itemDetail->id]) }}" class="btn btn-danger btn-lg btn-block" onClick="buyAlert(event);return false;">購入する</a>
        @elseif($itemDetail->buyer_id == Auth::id())
          <a href="{{ action('dealingBuyerController@statusBuyer',
            ['id' => $dealingStatus->id]) }}" class="btn btn-primary btn-lg btn-block">取引場面へ
          </a>
        @endif
      @else
        @if($itemDetail->buyer_id < 1)
          <a href="{{ action('itemController@itemEditGet',
            ['id' => $itemDetail->id]) }}"
            class="btn btn-success btn-lg btn-block">
            @if(Auth::user()->role_id == 1)
              <strong>管理者として</strong>商品を編集する
            @else
              商品を編集する
            @endif
          </a>
          <a id="delete" href="{{ action('itemController@itemDelete',
            ['id' => $itemDetail->id]) }}"
            class="btn btn-danger" onClick="deleteAlert(event);return false;">
            @if(Auth::user()->role_id == 1)
              <strong>管理者として</strong>商品を削除する
            @else
              商品を削除する
            @endif
          </a>
        @elseif($itemDetail->user_id == Auth::id())
          <a href="{{ action('dealingSellerController@statusSeller',
            ['id' => $dealingStatus->id]) }}"
            class="btn btn-primary btn-lg btn-block">取引場面へ
          </a>
        @endif
      @endif
    </div>
  </div>
</div>
<br>
<div class="description">
  <div class="row">
    <div class="col-md-12">
      <h2 class="text-break">{{ $itemDetail->description }}</h2>
    </div>
  </div>
</div>
<br>
<div class="goods">
  <div class="row">
    <div class="col-md-6">
      @guest
        @if($itemDetail->buyer_id < 1)
          <p>{{ count($goods) }}人が「いいね」をしました．</p>
          <a href="{{ route('login') }}" class="btn btn-danger">ログインして「いいね」をする</a>
        @else
          <p>{{ count($goods) }}人が「いいね」をしました．</p>
        @endif
      @else
        @if(count($goodCount) < 1 && $itemDetail->buyer_id < 1 && Auth::id() != $itemDetail->user_id)
          <a href="{{ action('itemController@itemDetailGood', ['id' => $itemDetail->id]) }}" class="btn btn-danger">いいね（{{ count($goods) }}人が「いいね」をしています）</a>
        @elseif(count($goodCount) > 0 && $itemDetail->buyer_id < 1)
          <p>（{{ count($goods) }}人が「いいね」をしています）</p>
          <a href="{{ action('itemController@itemDetailGood', ['id' => $itemDetail->id]) }}" class="btn btn-danger">「いいね」を取り消す</a>
        @else
          <p>{{ count($goods) }}人が「いいね」をしました．</p>
        @endif
      @endguest
    </div>
  </div>
</div>
<div class="itemCommentsArea">
  <div class="row">
    <div class="col-md-12">
      @if(count($watchers) > 0)
        <h1 style="text-align: center">コメント</h1>
      @else
        <h1 style="text-align: center">コメントはありません．</h1>
      @endif
      <form action="{{ action('itemController@itemDetailComment', ['id' => $itemDetail->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @guest
          @if($itemDetail->buyer_id < 1)
            <a href="{{ route('login') }}" class="btn btn-danger">ログインしてコメントを登録する</a>
          @endif
        @else
          @if($itemDetail->buyer_id < 1)
            @if(count($watchers) > 0 && $itemDetail->user_id == Auth::id() && isset($watcher->watcher->nickName))
              <div class="form-group row">
                <div class="col-md-4">
                  <label for="commentTo_id">コメントの相手を選択してください <span class="badge badge-danger">必須</span></label>
                  <select class="form-control" name="commentTo_id" id="commentTo_id" required>
                    <option value="">選択してください</option>
                    @foreach($watchers as $watcher)
                      <option value="{{ $watcher->watcher_id }}">{{ $watcher->watcher->nickName }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <textarea class="form-control" name="comment" id="comment" rows="5" cols="5" placeholder="コメントを入力してください．" required></textarea>
              <button type="submit" class="btn btn-danger" onClick="postAlert(event);return false;">コメントを登録する</button>
            @elseif($itemDetail->user_id != Auth::id())
              <textarea class="form-control" name="comment" id="comment" rows="5" cols="5" placeholder="コメントを入力してください．" required></textarea>
              <button type="submit" class="btn btn-danger" onClick="postAlert(event);return false;">コメントを登録する</button>
            @endif
          @endif   
        @endguest
      </form>
      
      <div class="row">
        <div class="itemComments col-md-12">
        @foreach($comments as $comment)
          @if($comment->watcher_id == Auth::id())
            <div class="rightComments col-md-6 offset-md-6">
              @if($comment->commentDelete < 1)
                <h5>{{ $comment->comment }}</h5>
              @else
                <h5>コメントを削除しました．</h5>
              @endif
              {{-- 以下のuserはCapp\Item_comment.phpで定義したusersテーブルを参照するための
              userメソッドであり，nickNameはusersテーブルのカラムである．
              なおユーザーが削除されても販売済みの商品は残るようにしてあるため，
              以下の記載が必要になる． --}}
              @if(!isset($comment->watcher->nickName))
                <p>このユーザーは削除されました．</p>
              @else
                <p>{{ $comment->watcher->nickName }}
                  <strong>
                    @if($comment->user_id == $comment->watcher_id)（出品者）
                    @else（質問者）
                    @endif
                  </strong>
                </p>
              @endif
                <p>{{ $comment->created_at }}</p>
              @if($itemDetail->buyer_id < 1 && $comment->commentDelete < 1 && $comment->watcher_id == Auth::id())
                <p>
                  <a class="btn btn-danger" href="{{ action('itemController@itemCommentDelete',
                    ['id' => $comment->id]) }}" onClick="deleteAlert(event);return false;">コメントを削除する
                  </a>
                </p>
              @endif
            </div>
          @else
            <div class="leftComments col-md-6">
              @if($comment->commentDelete < 1)
                <h5>{{ $comment->comment }}</h5>
              @else
                <h5>コメントを削除しました．</h5>
              @endif
              {{-- 以下のuserはCapp\Item_comment.phpで定義したusersテーブルを参照するための
              userメソッドであり，nickNameはusersテーブルのカラムである．
              なおユーザーが削除されても販売済みの商品は残るようにしてあるため，
              以下の記載が必要になる． --}}
              @if(!isset($comment->watcher->nickName))
                <p>このユーザーは削除されました．</p>
              @else
                <p>{{ $comment->watcher->nickName }}
                  <strong>
                    @if($comment->user_id == $comment->watcher_id)（出品者）
                    @else（質問者）
                    @endif
                  </strong>
                </p>
              @endif
              <p>{{ $comment->created_at }}</p>
            </div>
          @endif
        @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection