<!DOCTYPE html>
<html lang="ja">

    <script src="{{ asset('js/app.js') }}" defer></script>

    <p>{{ $superUser->nickName }}（管理者）様</p>
    <br>
    <p>{{ $inquirer->nickName }}様から以下のようなお問い合わせが届いておりますので，
        確認をお願いいたします．</p>
    <br>
    <p>
        <a href="{{ action('userController@userInquiryGet',
            ['id' => $inquirer->id]) }}">
            お問い合わせのページはこちら
        </a>
    </p>
    <br>
    <p>{{ $inquiry->inquiry }}</p>

</html>