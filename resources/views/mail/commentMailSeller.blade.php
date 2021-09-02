<!DOCTYPE html>
<html lang="ja">

    <script src="{{ asset('js/app.js') }}" defer></script>

    <p>{{ $watcher->nickName }}様</p>
    <br>
    <p>ご利用ありがとうございます．フ・リ・マ☆Sampleです．</p>
    <p>商品「{{ $itemDetail->name }}」について{{ $watcher->nickName }}様から以下のようなコメントが届いておりますので確認をお願いいたします．</p>
    <br>
    <p>
        <a href="{{ action('itemController@itemDetail',
            ['id' => $itemDetail->id]) }}">
            「{{ $itemDetail->name }}」のページはこちら
        </a>
    </p>
    <br>
    <p>{{ $comment->comment }}</p>

</html>