<!DOCTYPE html>
<html lang="ja">

    <script src="{{ asset('js/app.js') }}" defer></script>

    <p>{{ $dealingStatus->buyer->nickName }}様</p>
    <br>
    <p>ご利用ありがとうございます．フ・リ・マ☆Sampleです．</p>
    <p>{{ $dealingStatus->seller->nickName }}様が
        あなたを評価し，商品「{{ $item->name }}」の取引は終了しました．
    </p>

</html>