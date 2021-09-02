<!DOCTYPE html>
<html lang="ja">

    <script src="{{ asset('js/app.js') }}" defer></script>

    <p>{{ $dealingStatus->buyer->nickName }}様</p>
    <br>
    <p>ご利用ありがとうございます．フ・リ・マ☆Sampleです．</p>
    <p>
        {{ $dealingStatus->seller->nickName }}様が
        商品「{{ $item->name }}」を発送しました．
    </p>
    <p>
        @if($item->shippingMethod == 1)<a href=https://trackings.post.japanpost.jp/services/srv/search/input>レターパックの伝票番号は「{{ $dealingStatus->shippingNumber }}」です．</a>
        @elseif($item->shippingMethod == 2)<a href="https://trackings.post.japanpost.jp/services/srv/search/input">ゆうパックの伝票番号は「{{ $dealingStatus->shippingNumber }}」です．</a>
        @elseif($item->shippingMethod == 3)<a href="https://toi.kuronekoyamato.co.jp/cgi-bin/tneko">ヤマト運輸の伝票番号は「{{ $dealingStatus->shippingNumber }}」です．</a>
        @elseif($item->shippingMethod == 4)<a href="https://k2k.sagawa-exp.co.jp/p/sagawa/web/okurijoinput.jsp">佐川急便の伝票番号は「{{ $dealingStatus->shippingNumber }}」です．</a>
        @endif
    </p>
    <p>
        <a href="{{ action('dealingBuyerController@statusBuyer',
            ['id' => $dealingStatus->id]) }}">
            「{{ $item->name }}」の取引ページはこちら
        </a>
    </p>

</html>