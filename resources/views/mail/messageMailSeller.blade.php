<!DOCTYPE html>
<html lang="ja">

    <script src="{{ asset('js/app.js') }}" defer></script>

    <p>{{ $seller->nickName }}様</p>
    <br>
    <p>ご利用ありがとうございます．フ・リ・マ☆Sampleです．</p>
    <p>取引中の商品「{{ $item->name }}」について{{ $buyer->nickName }}様から
        以下のようなメッセージが届いておりますので，<br>確認をお願いいたします．</p>
    <br>
    <p>
        <a href="{{ action('dealingSellerController@statusSeller',
            ['id' => $dealingStatus->id]) }}">
            「{{ $item->name }}」の取引ページはこちら
        </a>
    </p>
    <br>
    <p>{{ $Message->message }}</p>

</html>