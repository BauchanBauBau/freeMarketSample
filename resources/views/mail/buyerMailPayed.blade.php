<!DOCTYPE html>
<html lang="ja">

    <script src="{{ asset('js/app.js') }}" defer></script>

    <p>{{ $dealingStatus->seller->nickName }}様</p>
    <br>
    <p>ご利用ありがとうございます．フ・リ・マ☆Sampleです．</p>
    <p>{{ $dealingStatus->buyer->nickName }}様が
        商品「{{ $item->name }}」の代金を支払いました．
    </p>
    <p>発送してください．</p>
    <p>
        <a href="{{ action('dealingSellerController@statusSeller',
            ['id' => $dealingStatus->id]) }}">
            「{{ $item->name }}」の取引ページはこちら
        </a>
    </p>

</html>