<!DOCTYPE html>
<html lang="ja">

    <script src="{{ asset('js/app.js') }}" defer></script>

    <p>{{ $dealingStatus->buyer->nickName }}様</p>
    <br>
    <p>ご利用ありがとうございます．フ・リ・マ☆Sampleです．</p>
    <p>商品「{{ $item->name }}」を{{ $dealingStatus->seller->nickName }}様から購入しました．</p>
    <p>取引を開始してください．</p>
    <br>
    <p>
        <a href="{{ action('dealingBuyerController@statusBuyer',
            ['id' => $dealingStatus->id]) }}">
            「{{ $item->name }}」の取引ページはこちら
        </a>
    </p>

</html>