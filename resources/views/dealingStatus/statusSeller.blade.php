@extends('layouts.top')

<link href="{{ asset('css/dealingStatus.css') }}" rel="stylesheet">
<script src="{{ asset('js/status.js') }}" defer></script>
<title>取引状況</title>

@section('content')

<div class="Status">
@if($dealingStatus->payed < 1)
    <h2>支払いをお待ちください</h2>
@elseif($dealingStatus->payed > 0 && $dealingStatus->shipped < 1)
    <h2>発送してください</h2>
    <div class="shippingAddress">
        <h3>（発送先）</h3>
        <h3>〒{{ $dealingStatus->buyer->postalCode }}</h3>
        <h3>{{ $dealingStatus->buyer->addressPref }}</h3>
        <h3>{{ $dealingStatus->buyer->addressCity }}</h3>
        <h3>{{ $dealingStatus->buyer->addressOther }}</h3>
        <h3>{{ $dealingStatus->buyer->name }} 様</h3>
    </div>

    @if($dealingStatus->item->shippingMethod > 1 && $dealingStatus->item->shippingMethod < 6)
        <h2><strong>（注意）</strong></h2>
        <h2><strong>発送後に伝票番号を記載する必要があるため，</strong></h2>
        <h2><strong>紛失しないようご留意ください．</strong></h2>

        <form action="{{ action('dealingSellerController@statusSellerShipped', ['id' => $dealingStatus->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
            <div class="shipped">
                <label for="shippingNumber">伝票番号</label>
                <input class="form-control col-md-6 offset-3" name="shippingNumber" id="shippingNumber" placeholder="伝票番号を入力してください．" required>
                <button type="submit" class="btn btn-danger" onClick="confirmAlert(event);return false;">発送が完了したことを通知する</button>
            </div>
        </form>
    @else
        <form action="{{ action('dealingSellerController@statusSellerShipped', ['id' => $dealingStatus->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="shipped">
                    <button type="submit" class="btn btn-danger" onClick="confirmAlert(event);return false;">発送が完了したことを通知する</button>
                </div>
        </form>
    @endif

@elseif($dealingStatus->payed > 0 && $dealingStatus->shipped > 0 && $dealingStatus->evaluated < 1)
    <h2>商品が到着し，購入者から評価されるまでお待ちください．</h2>
    <h2>
        @if($item->shippingMethod == 1)<a href=https://trackings.post.japanpost.jp/services/srv/search/input>レターパックの伝票番号は「{{ $dealingStatus->shippingNumber }}」です．</a>
        @elseif($item->shippingMethod == 2)<a href="https://trackings.post.japanpost.jp/services/srv/search/input">ゆうパックの伝票番号は「{{ $dealingStatus->shippingNumber }}」です．</a>
        @elseif($item->shippingMethod == 3)<a href="https://toi.kuronekoyamato.co.jp/cgi-bin/tneko">ヤマト運輸の伝票番号は「{{ $dealingStatus->shippingNumber }}」です．</a>
        @elseif($item->shippingMethod == 4)<a href="https://k2k.sagawa-exp.co.jp/p/sagawa/web/okurijoinput.jsp">佐川急便の伝票番号は「{{ $dealingStatus->shippingNumber }}」です．</a>
        @endif
    </h2>
</div>

@elseif($dealingStatus->payed > 0 && $dealingStatus->shipped > 0 &&
    $dealingStatus->evaluated > 0 && $dealingStatus->evaluated < 2)
    <h2>購入者を評価してください．</h2>

    <form action="{{ action('dealingEvaluationController@sellerEvaluation', ['id' => $dealingStatus->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="buyerEvaluation">購入者の評価 <span class="badge badge-danger">必須</span></label>
            <select class="form-control col-md-1" name="buyerEvaluation" id="buyerEvaluation">
                <option value="0">良い</option>
                <option value="1">悪い</option>
            </select>
        </div>
        <div class="form-group">
            <label for="buyerComment">購入者へのコメント</label>
            <textarea class="form-control" name="buyerComment" id="buyerComment" rows="10" cols="10" placeholder="購入者へのコメントをご記入ください（必須ではありません）．"></textarea>
        </div>
        <button type="submit" class="btn btn-danger btn-lg btn-block" onClick="confirmAlert(event);return false;">購入者の評価を完了する</button>
    </form>
@else
    <h2>この取引は終了しました．</h2>
@endif

<div class="name">
    <div class="row">
      <div class="col-md-12">
        <h1>{{ $item->name }}</h1>
      </div>
    </div>
</div>

<div class="info">
    <div class="row">
        <div class="col-md-6">
            <div class="img">
                @if(isset($item->image))
                <img class="img-thumbnail" src="{{ $item->image }}">
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
                                @if(!isset($item->user->nickName))
                                  出品したユーザー情報は削除されました．
                                @else
                                  <a href="{{ action('userController@userRegisteredItem',
                                    ['id' => $item->user_id]) }}">{{ $item->user->nickName }}
                                  </a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>カテゴリ</td>
                            <td>{{ $item->category->name }}</td>
                        <tr>
                            <td>商品の状態</td>
                            <td>
                                @if($item->condition == 0)新品・未使用
                                @elseif($item->condition == 1)新品・未使用に近い
                                @elseif($item->condition == 2)目立った傷や汚れ無し
                                @elseif($item->condition == 3)傷や汚れ有り
                                @elseif($item->condition == 4)全体的に状態が悪い
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>送料</td>
                            <td>
                                @if($item->shippingOption == 0)送料込み
                                @else 着払い
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>発送方法</td>
                            <td>
                                @if($item->shippingMethod == 0)普通郵便
                                @elseif($item->shippingMethod == 1)レターパック
                                @elseif($item->shippingMethod == 2)ゆうパック
                                @elseif($item->shippingMethod == 3)ヤマト運輸
                                @elseif($item->shippingMethod == 4)佐川急便
                                @elseif($item->shippingMethod == 5)その他(荷物の位置を特定できる伝票番号あり)
                                @elseif($item->shippingMethod == 6)その他(荷物の位置を特定できる伝票番号なし)
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>発送元地域</td>
                            <td>
                                @if(!isset($item->user->addressPref))
                                  出品したユーザー情報は削除されました．
                                @else
                                  {{ $item->user->addressPref }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>発送の目安</td>
                            <td>{{ $item->days }}日</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="price">
    <div class="row">
      <div class="col-md-12">
        <h1>￥{{ number_format($item->price) }}</h1>
        <h5>（税込）</h5>
        <h5>
          @if($item->shippingOption == 0)送料込み
          @else 着払い
          @endif
        </h5>
      </div>
    </div>
</div>

<div class="dealingMessageArea">
    <div class="row">
        <div class="col-md-12">
            <h1>取引メッセージ</h1>
            <form action="{{ action('dealingSellerController@dealingMessage', ['id' => $dealingStatus->id]) }}" method="post" enctype="multipart/form-data">
            @csrf
                @if($dealingStatus->evaluated < 2)
                    <textarea class="form-control" name="message" id="message" rows="5" cols="5" placeholder="メッセージを入力してください．" required></textarea>
                    <div class="messageButton">
                        <button type="submit" class="btn btn-danger" onClick="postAlert(event);return false;">メッセージを登録する</button>
                    </div>
                @endif
            </form>
            
            <div class="row">
                <div class="dealingMessages col-md-12">
                    @foreach($messages as $message)
                        @if($message->user_id == Auth::id())
                            <div class="rightMessages col-md-6 offset-md-6">
                            @if($message->messageDelete < 1)
                                <h5>{{ $message->message }}</h5>
                            @else
                                <h5>メッセージを削除しました．</h5>
                            @endif
                                {{-- 以下のuserはapp\Dealing_message.phpで定義したusersテーブルを参照するための
                                userメソッドであり，nickNameはusersテーブルのカラムである． --}}
                            @if(!isset($message->user->nickName))
                                <p>このユーザーは削除されました．</p>
                            @else
                                <p>{{ $message->user->nickName }}
                                    <strong>
                                        @if($message->buyer_id == $message->user_id)（購入者）
                                        @else（出品者）
                                        @endif
                                    </strong>
                                </p>
                            @endif
                                <p>{{ $message->created_at }}</p>
                            @if($message->messageDelete < 1 && $dealingStatus->evaluated < 2 && $message->user_id == Auth::id())
                                <p>
                                    <form action="{{ action('dealingSellerController@dealingMessageDelete', 
                                        ['id' => $message->id]) }}" method="post">
                                        @csrf
                                            <button type="submit" class="btn btn-danger offset-md-4" onClick="deleteAlert(event);return false;">メッセージを削除する</button>
                                    </form> 
                                </p>
                            @endif
                            </div>
                        @else
                            <div class="leftMessages col-md-6">
                                @if($message->messageDelete < 1)
                                    <h5>{{ $message->message }}</h5>
                                @else
                                    <h5>メッセージを削除しました．</h5>
                                @endif
                                {{-- 以下のuserはapp\Dealing_message.phpで定義したusersテーブルを参照するための
                                userメソッドであり，nickNameはusersテーブルのカラムである． --}}
                                @if(!isset($message->user->nickName))
                                    <p>出品したユーザー情報は削除されました．</p>
                                @else
                                    <p>{{ $message->user->nickName }}
                                        <strong>
                                            @if($message->buyer_id == $message->user_id)（購入者）
                                            @else（出品者）
                                            @endif
                                        </strong>
                                    </p>
                                @endif
                                <p>{{ $message->created_at }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection