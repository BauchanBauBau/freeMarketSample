<!DOCTYPE html>
<html lang="ja">

    <script src="{{ asset('js/app.js') }}" defer></script>

    <p>{{ $commentTo->nickName }}様</p>
    <br>
    <p>ご利用ありがとうございます．フ・リ・マ☆Sampleです．</p>
    <p>商品「{{ $itemDetail->name }}」について{{ $seller->nickName }}様から
        以下のようなコメントが届いておりますので確認をお願いいたします．
    </p>
    <p>
        <a href="{{ action('itemController@itemDetail',
            ['id' => $itemDetail->id]) }}">
            「{{ $itemDetail->name }}」のページはこちら
        </a>
    </p>
    <br>
    <p>{{ $comment->comment }}</p>

</html>