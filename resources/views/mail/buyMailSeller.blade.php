<!DOCTYPE html>
<html lang="ja">

    <script src="{{ asset('js/app.js') }}" defer></script>

    <p>{{ $dealingStatus->seller->nickName }}様</p>
    <br>
    <p>ご利用ありがとうございます．フ・リ・マ☆Sampleです．</p>
    <p>商品「{{ $item->name }}」を{{ $dealingStatus->buyer->nickName }}様が購入しました．</p>
    <p>取引を開始してください．</p>
    <br>
    <p>
        <a href="{{ action('dealingSellerController@statusSeller',
            ['id' => $dealingStatus->id]) }}">
            「{{ $item->name }}」の取引ページはこちら
        </a>
    </p>

</html>