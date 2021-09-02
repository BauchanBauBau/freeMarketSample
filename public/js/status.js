/*
<script src="{{ asset('js/status.js') }}" defer></script>
onClick="xxxAlert(event);return false;"
*/


//投稿確認画面
function postAlert(e){
    if(!window.confirm('メッセージを送信しますか？')){
       return false;
    }
    document.submit();
  };

  //削除確認画面
function deleteAlert(e){
    if(!window.confirm('メッセージを削除しますか？')){
       return false;
    }
    document.submit();
  };

//支払・発送等確認画面
function confirmAlert(e){
    if(!window.confirm('実行してもよろしいですか？')){
       return false;
    }
    document.submit();
  };
