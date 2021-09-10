<!DOCTYPE html>
<html lang="ja">

    <script src="{{ asset('js/app.js') }}" defer></script>

    <p>{{ $inquirer->nickName }}様</p>
    <br>
    <p>ご利用ありがとうございます．フ・リ・マ☆Sampleです．</p>
    <p>管理者から以下のような問い合わせがありました．</p>
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